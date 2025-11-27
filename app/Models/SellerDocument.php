<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $document_type
 * @property string $file_path
 * @property string|null $original_name
 * @property string $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereUploadedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerDocument whereUserId($value)
 * @mixin \Eloquent
 */
class SellerDocument extends Model
{
    protected $fillable = ['user_id', 'document_type', 'file_path', 'original_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}