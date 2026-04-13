<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelepasanInformasi extends Model
{
    protected $table = 'pelepasan_informasi';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'no_surat',
        'no_rekamedis',
        'nama',
        'no_telp',
        'status',
        'created_date'
    ];

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getAttribute($keyName));
        }

        return $query;
    }
}
