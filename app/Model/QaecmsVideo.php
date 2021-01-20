<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class QaecmsVideo extends Model
{
    use Searchable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!qae_search()) {
            self::disableSearchSyncing();
        }
    }

    protected $fillable = ['title', 'introduction', 'seokey', 'thumbnail', 'sid', 'stid', 'stype', 'lang', 'area', 'year', 'note', 'score', 'actor', 'director', 'shost', 'last', 'content', 'editor', 'status', 'vip', 'integrall', 'type','onlykey'];

    public function type()
    {
        return $this->belongsTo(QaecmsType::class, 'type', 'id');
    }

    public function job()
    {
        return $this->belongsTo(QaecmsJob::class, 'shost', 'api');
    }


    public function searchableAs()
    {
        return 'videos_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $pusharr = [
            'id' => $array['id'],
            'title' => $array['title'],
            'stype' => $array['stype'],
            'lang' => $array['lang'],
            'area' => $array['area'],
            'note' => $array['note'],
            'actor' => $array['actor'],
            'director' => $array['director'],
            'introduction' => $array['introduction'],
            'seokey' => $array['seokey'],
            'status' => $array['status'] ?? 0,
            'vip' => $array['vip'] ?? 0
        ];
        return $pusharr;
    }

    /**
     * insert or update a record
     *
     * @param array $values
     * @param array $value
     * @return bool
     */
    public function insertOrUpdate(array $values, array $value)
    {
        $connection = $this->getConnection();   // 数据库连接
        $builder = $this->newQuery()->getQuery();   // 查询构造器
        $grammar = $builder->getGrammar();  // 语法器
        // 编译插入语句
        $insert = $grammar->compileInsert($builder, $values);
        // 编译重复后更新列语句。
        $update = $this->compileUpdateColumns($grammar, $value);
        // 构造查询语句
        $query = $insert.' on duplicate key update '.$update;
        // 组装sql绑定参数
        $bindings = $this->prepareBindingsForInsertOrUpdate($values, $value);
        // 执行数据库查询
        return $connection->insert($query, $bindings);
    }

    /**
     * Compile all of the columns for an update statement.
     * @param $grammar
     * @param array $values
     * @return string
     */
    private function compileUpdateColumns($grammar, $values)
    {
        return collect($values)->map(function ($value, $key) use ($grammar) {
            return $grammar->wrap($key).' = '.$grammar->parameter($value);
        })->implode(', ');
    }

    /**
     * Prepare the bindings for an insert or update statement.
     *
     * @param array $values
     * @param array $value
     * @return array
     */
    private function prepareBindingsForInsertOrUpdate(array $values, array $value)
    {
        // Merge array of bindings
        $bindings = array_merge_recursive($values, $value);
        // Remove all of the expressions from a list of bindings.
        return array_values(array_filter(array_flatten($bindings, 1), function ($binding) {
            return ! $binding instanceof \Illuminate\Database\Query\Expression;
        }));
    }

}
