<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Medium
 * 
 * @property int $id
 * @property string $reference
 * @property \Carbon\Carbon $created_at
 * @property string $type
 * @property string $description
 * @property string $title
 * @property int $company_id
 * @property string $directory
 * @property Carbon $deleted_at
 * @property int $deleted_by
 * @property int $uploaded_by
 * @property int $size
 * 
 * @property \App\Models\Company $owning_company
 * @property \Illuminate\Database\Eloquent\Collection $media_links
 * @property User $deleter
 *
 * @package App\Models
 */
class Medium extends Eloquent
{

	public $timestamps = false;

	protected $casts = [
		'uploaded_by' => 'int',
        'company_id' => 'int'
	];

	protected $dates = [
		'modified_at',
        'deleted_at'
	];

	protected $fillable = [
		'reference',
		'type',
		'description',
		'title',
		'company_id',
		'directory',
	];

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class, 'company_id');
	}

	public function media_links()
	{
		return $this->hasMany(\App\Models\MediaLink::class, 'media_id');
	}

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

	public function saveFile(UploadedFile $file, $folder, $permission)
	{

		$storage = \Storage::disk('public');
		$name = time() . rand(1, 9999) . "." . $file->getClientOriginalExtension();
		if($file->getClientOriginalExtension() != 'pdf' && $file->getClientOriginalExtension() != 'PDF') {
			$this->prepareForUpload($file->getPathname(), $name);
			if ($storage->put($folder . '/original_' . $name, file_get_contents($file->getPathname()), $permission)
				&& $storage->put($folder . '/thumb_' . $name, file_get_contents('/tmp/thumb_' . $name), $permission)
				&& $storage->put($folder . '/small_' . $name, file_get_contents('/tmp/small_' . $name), $permission)
				&& $storage->put($folder . '/medium_' . $name, file_get_contents('/tmp/medium_' . $name), $permission)
				&& $storage->put($folder . '/large_' . $name, file_get_contents('/tmp/large_' . $name), $permission)
			) {

				unlink('/tmp/thumb_' . $name);
				unlink('/tmp/small_' . $name);
				unlink('/tmp/medium_' . $name);
				unlink('/tmp/large_' . $name);
				return $name;
			} else {
				return false;
			}
		}else{
			if($storage->put($folder . '/' . $name, file_get_contents($file->getPathname()), $permission)){
				return $name;
			}

			return false;
		}

	}

	private function prepareForUpload($filepath, $filename)
	{
		if (is_file($filepath)) {
			$thumb = new \Imagick(realpath($filepath));
			$thumb->stripImage();
			$thumb->resizeImage(150, 0, \Imagick::FILTER_LANCZOS, 1);
			$thumb->writeImage("/tmp/thumb_" . $filename);

			$small = new \Imagick(realpath($filepath));
			$small->stripImage();
			$small->resizeImage(300, 0, \Imagick::FILTER_LANCZOS, 1);
			$small->writeImage("/tmp/small_" . $filename);

			$medium = new \Imagick(realpath($filepath));
			$medium->stripImage();
			$medium->resizeImage(600, 0, \Imagick::FILTER_LANCZOS, 1);
			$medium->writeImage("/tmp/medium_" . $filename);

			$large = new \Imagick(realpath($filepath));
			$large->stripImage();
			$large->resizeImage(1000, 0, \Imagick::FILTER_LANCZOS, 1);
			$large->writeImage("/tmp/large_" . $filename);
		}

		return true;
	}

	public function getPath($size = null)
	{
        if($this->getAttribute('type') == 'image') {
            if(!$size){
                $size = 'original';
            }
            return  '/' . $this->getAttribute('company_id')
            . "/" . $this->getAttribute('directory') . "/" . $size . "_" . $this->getAttribute('reference');
        }else{
            return  '/' . $this->getAttribute('company_id')
            . "/" . $this->getAttribute('directory') . "/" . $this->getAttribute('reference');
        }
	}

    public function getPublicPath($size = null)
    {
        if($this->getAttribute('type') == 'image') {
            if(!$size){
                $size = 'small';
            }
            return env('API_URL') . '/storage/app/public/' . $this->getAttribute('company_id')
                . "/" . $this->getAttribute('directory') . "/" . $size . "_" . $this->getAttribute('reference');
        }else{
            return env('API_URL') . '/storage/app/public/' . $this->getAttribute('company_id')
                . "/" . $this->getAttribute('directory') . "/" . $this->getAttribute('reference');
        }
    }

    public function getPublicData($excludeFields = [])
    {
        $media = $this->toArray();
        $media['path'] = $this->getPath();
        $media['url'] = $this->getPublicPath('medium');
        return $media;
    }


}
