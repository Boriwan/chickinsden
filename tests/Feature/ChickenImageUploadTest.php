<?php

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::factory()->regular()->create();
    $this->breed = Breed::factory()->create();

    $this->payload = [
        'name' => 'PhotoHen',
        'gender' => 'female',
        'birth_date' => '2024-07-07',
        'breed_id' => $this->breed->id,
        'height' => 30,
        'weight' => 'light',
    ];
});

it('stores an uploaded photo on the public disk', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('hen.jpg'),
        ])
        ->assertRedirect();

    $chicken = $this->user->chickens()->firstOrFail();

    expect($chicken->image)->not->toBeNull()
        ->and($chicken->image)->toStartWith('chickens_imgs/');

    Storage::disk('public')->assertExists($chicken->image);
});

it('does not keep the name the browser sent', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('../../escape.jpg'),
        ]);

    $path = $this->user->chickens()->firstOrFail()->image;

    // A client-supplied name could climb out of the uploads directory.
    expect($path)->toStartWith('chickens_imgs/')
        ->and($path)->not->toContain('..');
});

it('rejects a file that is not an image', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
        ])
        ->assertSessionHasErrors('image');

    expect($this->user->chickens()->count())->toBe(0);
});

it('rejects an svg, which can carry script', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->create('payload.svg', 10, 'image/svg+xml'),
        ])
        ->assertSessionHasErrors('image');

    expect($this->user->chickens()->count())->toBe(0);
});

it('rejects an image over the eight megabyte limit', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('huge.jpg')->size(9000),
        ])
        ->assertSessionHasErrors('image');

    expect($this->user->chickens()->count())->toBe(0);
});

it('says plainly that the photo was too large', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('huge.jpg')->size(9000),
        ])
        ->assertSessionHasErrors([
            'image' => 'That photo is too large. Please choose one under 8 MB, or take the photo again at a lower resolution.',
        ]);
});

it('stores a chicken with no photo at all', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload)
        ->assertRedirect();

    expect($this->user->chickens()->firstOrFail()->image)->toBeNull();
});

it('replaces the photo on edit and drops the old file', function () {
    $chicken = Chicken::factory()->create(['user_id' => $this->user->id, 'breed_id' => $this->breed->id]);
    $old = 'chickens_imgs/original.jpg';
    $chicken->update(['image' => $old]);
    Storage::disk('public')->put($old, 'x');

    $this->actingAs($this->user)
        ->put(route('chickens.update', $chicken), $this->payload + [
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

    $fresh = $chicken->fresh();

    expect($fresh->image)->not->toBe($old);

    Storage::disk('public')->assertExists($fresh->image);
    Storage::disk('public')->assertMissing($old);
});

it('keeps the current photo when the edit sends no file', function () {
    $chicken = Chicken::factory()->create(['user_id' => $this->user->id, 'breed_id' => $this->breed->id]);
    $chicken->update(['image' => 'chickens_imgs/keep.jpg']);
    Storage::disk('public')->put('chickens_imgs/keep.jpg', 'x');

    $this->actingAs($this->user)
        ->put(route('chickens.update', $chicken), $this->payload);

    expect($chicken->fresh()->image)->toBe('chickens_imgs/keep.jpg');
    Storage::disk('public')->assertExists('chickens_imgs/keep.jpg');
});

it('keeps a photo another chicken still points at', function () {
    $shared = 'chickens_imgs/shared.jpg';
    Storage::disk('public')->put($shared, 'x');

    $chicken = Chicken::factory()->create(['user_id' => $this->user->id, 'breed_id' => $this->breed->id]);
    $chicken->update(['image' => $shared]);

    $other = Chicken::factory()->create(['user_id' => $this->user->id, 'breed_id' => $this->breed->id]);
    $other->update(['image' => $shared]);

    $this->actingAs($this->user)
        ->put(route('chickens.update', $chicken), $this->payload + [
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

    Storage::disk('public')->assertExists($shared);
});

it('removes the photo when its chicken is deleted', function () {
    $chicken = Chicken::factory()->create(['user_id' => $this->user->id]);
    $chicken->update(['image' => 'chickens_imgs/gone.jpg']);
    Storage::disk('public')->put('chickens_imgs/gone.jpg', 'x');

    $this->actingAs($this->user)
        ->delete(route('chickens.destroy', $chicken))
        ->assertRedirect();

    Storage::disk('public')->assertMissing('chickens_imgs/gone.jpg');
});

it('never touches a file outside the uploads directory', function () {
    $chicken = Chicken::factory()->create(['user_id' => $this->user->id]);
    $chicken->update(['image' => 'seeder/seeded.jpg']);
    Storage::disk('public')->put('seeder/seeded.jpg', 'x');

    $this->actingAs($this->user)->delete(route('chickens.destroy', $chicken));

    Storage::disk('public')->assertExists('seeder/seeded.jpg');
});

it('keeps the original dimensions rather than resizing', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => widePhoto(),
        ]);

    $path = $this->user->chickens()->firstOrFail()->image;

    expect($path)->toEndWith('.jpg');

    $stored = decode(Storage::disk('public')->get($path));

    // 3000px is past the 1600px ceiling the compression pass used to apply.
    expect($stored->getImageWidth())->toBe(3000)
        ->and($stored->getImageHeight())->toBe(2000);
});

it('strips the exif block, which is where a phone records the location', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('located.jpg'),
        ]);

    $stored = decode(Storage::disk('public')->get($this->user->chickens()->firstOrFail()->image));

    expect($stored->getImageProfiles('*'))->toBe([]);
});

it('writes a jpeg whatever format arrived', function () {
    $this->actingAs($this->user)
        ->post(route('chickens.store'), $this->payload + [
            'image' => UploadedFile::fake()->image('photo.png'),
        ]);

    $path = $this->user->chickens()->firstOrFail()->image;

    expect($path)->toEndWith('.jpg')
        ->and(decode(Storage::disk('public')->get($path))->getImageFormat())->toBe('JPEG');
});

/**
 * A real 3000x2000 JPEG on disk, since UploadedFile::fake() cannot be given
 * dimensions and the point of this test is that they survive untouched.
 */
function widePhoto(): UploadedFile
{
    $path = sys_get_temp_dir().'/chicken-upload-test-'.uniqid().'.jpg';

    $image = new Imagick;
    $image->newImage(3000, 2000, new ImagickPixel('#6b4a2b'));
    $image->setImageFormat('jpeg');
    $image->writeImage($path);
    $image->clear();

    return new UploadedFile($path, 'wide.jpg', 'image/jpeg', null, true);
}

it('forbids a non-owner from swapping the photo', function () {
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);

    $this->actingAs(User::factory()->regular()->create())
        ->put(route('chickens.update', $chicken), $this->payload + [
            'image' => UploadedFile::fake()->image('nope.jpg'),
        ])
        ->assertForbidden();

    // The chicken started with no photo, so a null here proves nothing was saved.
    expect($chicken->fresh()->image)->toBeNull();
});

/**
 * Decode raw image bytes. Imagick's constructor treats its argument as a file
 * path, so stored bytes have to go through readImageBlob().
 */
function decode(string $bytes): Imagick
{
    $image = new Imagick;
    $image->readImageBlob($bytes);

    return $image;
}
