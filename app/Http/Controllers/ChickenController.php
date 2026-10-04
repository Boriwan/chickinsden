<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FlashesNotifications;
use App\Models\Breed;
use App\Models\Chicken;
use App\Models\ChickenTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Imagick;
use ImagickPixel;

class ChickenController extends Controller
{
    use FlashesNotifications;

    /** Largest photo accepted, in kilobytes. */
    private const PHOTO_MAX_KB = 8192;

    /**
     * Validation rules shared by store() and update().
     *
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|after_or_equal:1990-01-01|before_or_equal:today',
            'breed_id' => 'required|integer|exists:breeds,id',
            'height' => 'nullable|numeric|min:1|max:100',
            'weight' => 'nullable|in:light,medium,heavy',
            // The picker only submits existing ids, but nothing stops a
            // crafted request sending others, so they are checked anyway.
            'traits' => ['nullable', 'array'],
            'traits.*' => ['integer', 'exists:chicken_traits,id'],
            // 8 MB is comfortably above a modern phone photo while staying
            // well under the 32 MB php.ini and nginx allow, so an oversized
            // request is answered with a readable validation error rather than
            // a bare 413 from the web server.
            //
            // mimes narrows the image rule. Laravel's own list allows svg, and
            // an svg served from the app can carry script, so it is left out.
            // heic and heif stay in: an iPhone saves HEIC by default, and
            // dropping those would mean most phone photos were rejected.
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,heic,heif', 'max:'.self::PHOTO_MAX_KB],
        ];
    }

    /**
     * Messages for the validation rules, where the default wording is either
     * too technical or too blunt.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'image.max' => 'That photo is too large. Please choose one under 8 MB, or take the photo again at a lower resolution.',
            'image.mimes' => 'That file is not a supported photo. Please choose a JPEG, PNG, WebP or HEIC image.',
        ];
    }

    /**
     * Store an uploaded photo and return its path relative to the public disk.
     */
    private function storeImage(Request $request, Chicken $chicken): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $previous = $chicken->image;

        $chicken->image = $this->storePhoto($request->file('image'));
        $chicken->save();

        $this->deleteIfUnreferenced($previous, $chicken);
    }

    /**
     * Store an upload on the public disk, returning its path.
     *
     * The file is kept at the size it arrived. What is not kept is its
     * orientation and format: a phone records which way is up in EXIF rather
     * than rotating the pixels, and an iPhone saves HEIC, which Chrome and
     * Firefox cannot display. So the rotation is baked in, the EXIF block is
     * dropped, and the result is written as JPEG.
     */
    private function storePhoto(UploadedFile $file): string
    {
        $image = new Imagick($file->getRealPath());

        $this->applyExifOrientation($image);

        // Stripping removes the EXIF block, which is where a phone records the
        // photo's GPS coordinates.
        $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
        $image->stripImage();
        $image->setImageFormat('jpeg');

        // The name is generated rather than taken from the browser, which is
        // what keeps a crafted filename from escaping chickens_imgs.
        $path = 'chickens_imgs/'.Str::random(40).'.jpg';

        Storage::disk('public')->put($path, $image->getImageBlob());
        $image->clear();

        return $path;
    }

    /**
     * Apply the rotation an EXIF orientation tag asks for.
     *
     * Phones rarely rotate the pixels themselves; they record which way is up
     * and leave the image sideways. Without this, every portrait photo saved
     * from a phone displays on its side.
     */
    private function applyExifOrientation(Imagick $image): void
    {
        $background = new ImagickPixel('none');

        match ($image->getImageOrientation()) {
            2 => $image->flopImage(),
            3 => $image->rotateImage($background, 180),
            4 => $image->flipImage(),
            5 => $this->rotate($image->flopImage(), $background, 90),
            6 => $image->rotateImage($background, 90),
            7 => $this->rotate($image->flopImage(), $background, 270),
            8 => $image->rotateImage($background, 270),
            default => null,
        };
    }

    /**
     * Imagick's rotateImage returns the image for chaining, while match arms
     * must all evaluate to null, so the result is discarded.
     */
    private function rotate(Imagick $image, ImagickPixel $background, int $degrees): null
    {
        $image->rotateImage($background, $degrees);

        return null;
    }

    /**
     * Remove an image file once no chicken points at it any more.
     *
     * Two chickens can share one file, so the old photo is only deleted when
     * this was its last reference. Anything outside the uploads directory is
     * left alone entirely.
     */
    private function deleteIfUnreferenced(?string $path, Chicken $except): void
    {
        if ($path === null || ! str_starts_with($path, 'chickens_imgs/')) {
            return;
        }

        if (Chicken::where('image', $path)->whereKeyNot($except->id)->exists()) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * The traits offered by the chicken form.
     */
    private function selectableTraits(): Collection
    {
        return ChickenTrait::orderBy('name')->get();
    }

    /**
     * Show the chickens visible to the signed-in user.
     *
     * Admins see every chicken, everyone else sees only their own. Any
     * combination of ?gender=, ?breed= and ?trait= narrows either list, so the
     * dashboard cards and the chart legends can deep link into it.
     */
    public function index(Request $request): View
    {
        $query = Chicken::with(['breed', 'traits'])
            ->when(! $request->user()->is_admin, fn ($builder) => $builder->where('user_id', $request->user()->id));

        // Only values that resolve are applied, so a stale link falls back to
        // the unfiltered list rather than erroring or silently matching none.
        $gender = in_array($request->string('gender')->toString(), ['male', 'female'], true)
            ? $request->string('gender')->toString()
            : null;

        $breed = Breed::find($request->integer('breed') ?: null);
        $trait = ChickenTrait::find($request->integer('trait') ?: null);

        $query->when($gender, fn ($builder) => $builder->where('gender', $gender));
        $query->when($breed, fn ($builder) => $builder->where('breed_id', $breed->id));

        if ($trait !== null) {
            $query->whereHas('traits', fn ($builder) => $builder->where('chicken_traits.id', $trait->id));
        }

        $filters = array_filter([
            'gender' => $gender,
            'breed' => $breed,
            'trait' => $trait,
        ]);

        // withQueryString() carries ?gender, ?breed and ?trait onto each page link.
        // Without it a user who filtered down to one breed and then opened
        // page 2 would silently be shown the unfiltered list.
        $chickens = $query->latest()->paginate(15)->withQueryString();

        return view('chickens.index', compact('chickens', 'filters'));
    }

    /**
     * Show the form for creating a new chicken.
     */
    public function create(): View
    {
        return view('chickens.create', [
            'breeds' => Breed::orderBy('name')->get(),
            'traits' => $this->selectableTraits(),
        ]);
    }

    /**
     * Store a newly created chicken for the signed-in user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        // The upload and the trait ids are handled on their own, so neither
        // reaches mass assignment.
        $traits = $validated['traits'] ?? [];
        unset($validated['image'], $validated['traits']);

        $chicken = Chicken::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $this->storeImage($request, $chicken);

        // sync() rather than attach() so re-saving a chicken replaces the set
        // instead of stacking duplicates.
        $chicken->traits()->sync($traits);

        return $this->notify(
            $this->redirectToIndex($request),
            "{$chicken->name} was added.",
            'created',
        );
    }

    /**
     * Display the specified chicken.
     */
    public function show(Chicken $chicken): View
    {
        $this->authorize('view', $chicken);

        $chicken->load(['breed', 'traits']);

        return view('chickens.show', compact('chicken'));
    }

    /**
     * Show the form for editing the specified chicken.
     */
    public function edit(Chicken $chicken): View
    {
        $this->authorize('update', $chicken);

        return view('chickens.edit', [
            'chicken' => $chicken->load('traits'),
            'breeds' => Breed::orderBy('name')->get(),
            'traits' => $this->selectableTraits(),
        ]);
    }

    /**
     * Update the specified chicken.
     */
    public function update(Request $request, Chicken $chicken): RedirectResponse
    {
        $this->authorize('update', $chicken);

        $validated = $request->validate($this->rules(), $this->messages());

        // The traits key is never mass assigned, only synced, and the image is
        // stored on disk, so both are pulled out before update().
        $traits = $validated['traits'] ?? [];

        unset($validated['traits'], $validated['image']);

        $chicken->update($validated);
        $chicken->traits()->sync($traits);

        $this->storeImage($request, $chicken);

        return $this->notify(
            $this->redirectToIndex($request),
            "{$chicken->name} was updated.",
            'updated',
        );
    }

    /**
     * Delete the specified chicken.
     */
    public function destroy(Request $request, Chicken $chicken): RedirectResponse
    {
        $this->authorize('delete', $chicken);

        $name = $chicken->name;
        $image = $chicken->image;
        $chicken->delete();

        // Only once the row is gone, since the check counts other references.
        $this->deleteIfUnreferenced($image, $chicken);

        return $this->notify(
            $this->redirectToIndex($request),
            "{$name} was deleted.",
            'deleted',
        );
    }

    /**
     * Send admins back to the admin table and users back to their own list.
     */
    private function redirectToIndex(Request $request): RedirectResponse
    {
        return redirect()->route(
            $request->user()->is_admin ? 'admin.chickens.index' : 'chickens.index'
        );
    }
}
