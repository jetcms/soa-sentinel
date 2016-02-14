<?php namespace SleepingOwl\Admin\ColumnFilters;

use Illuminate\Support\Collection;
use SleepingOwl\Admin\AssetManager\AssetManager;
use SleepingOwl\Admin\Repository\BaseRepository;

class Select extends BaseColumnFilter
{

	protected $view = 'select';
	protected $model;
	protected $display = 'title';
	protected $options = [];
	protected $placeholder;
    protected $filter_field = '';
	protected $sort = true;

	/**
	 * Initialize column filter
	 */
	public function initialize()
	{
		parent::initialize();

		AssetManager::addScript('admin::default/scripts/column-filters/select.js');
	}

    public function filter_field( $field = null )
    {
        if(is_null($field))
            return $this->filter_field;

        $this->filter_field = $field;
        return $this;
    }

	public function model($model = null)
	{
		if (is_null($model))
		{
			return $this->model;
		}
		$this->model = $model;
		return $this;
	}

	public function display($display = null)
	{
		if (is_null($display))
		{
			return $this->display;
		}
		$this->display = $display;
		return $this;
	}

	public function options($options = null)
	{
		if (is_null($options))
		{
			if ( ! is_null($this->model()) && ! is_null($this->display()))
			{
				$this->loadOptions();
			}
			$options = $this->options;

			if( $this->sort() ) {
				asort($options);
			}

			return $options;
		}
		$this->options = $options;
		return $this;
	}

	protected function loadOptions()
	{
		$repository = new BaseRepository($this->model());
		$key = $repository->model()->getKeyName();
		$options = $repository->query()->get()->lists($this->display(), $key);
		if ($options instanceof Collection)
		{
			$options = $options->all();
		}
		$options = array_unique($options);
		$this->options($options);
	}

	public function placeholder($placeholder = null)
	{
		if (is_null($placeholder))
		{
			return $this->placeholder;
		}
		$this->placeholder = $placeholder;
		return $this;
	}

	public function getParams()
	{
		return parent::getParams() + [
			'options'     => $this->options(),
			'placeholder' => $this->placeholder(),
		];
	}

	public function apply($repository, $column, $query, $search, $fullSearch, $operator = '=')
	{
		#if (empty($search)) return;
        if ($search === '') return;

        if($this->filter_field())
        {
            $query->where($this->filter_field(), '=', $search);
            return;
        }

        if ($operator == 'like')
		{
			$search = '%' . $search . '%';
		}

		$name = $column->name();
		if ($repository->hasColumn($name))
		{
			$query->where($name, $operator, $search);
		} elseif (strpos($name, '.') !== false)
		{
			$parts = explode('.', $name);
			$fieldName = array_pop($parts);
			$relationName = implode('.', $parts);
			$query->whereHas($relationName, function ($q) use ($search, $fieldName, $operator)
			{
				$q->where($fieldName, $operator, $search);
			});
		}
	}

	protected function sort() {
		return $this->sort;
	}

	public function disableSort()
	{
		$this->sort = false;
		return $this;
	}

}
