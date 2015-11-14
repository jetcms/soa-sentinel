<?php namespace SleepingOwl\Admin\FormItems;

use Input;
use Illuminate\Support\Collection;
use SleepingOwl\Admin\AssetManager\AssetManager;
use SleepingOwl\Admin\Repository\BaseRepository;

class IRadio extends Radio
{

	protected $view 		= 'icheckbox';
	protected $skin			= '';
	protected $color		= '';
	protected $increase		= '20%';

	public function initialize()
	{
		parent::initialize();

		AssetManager::addStyle('admin::default/plugins/icheck/all.css');
		AssetManager::addScript('admin::default/plugins/icheck/js/icheck.min.js');
		AssetManager::addScript('admin::default/scripts/icheck/init.js');
	}

	public function skin($skin=null)
	{
		if (is_null($skin))
		{
			return $this->skin;
		}
		$this->skin = $skin;
		return $this;	
	}

	public function color($color=null)
	{
		if (is_null($color))
		{
			return $this->color;
		}
		$this->color = $color;
		return $this;	
	}

	public function increase($increase=null)
	{
		if (is_null($increase))
		{
			return $this->increase;
		}
		$this->increase = $increase;
		return $this;	
	}

	public function attributes()
	{
		$attributes = [
			'skin'			=> $this->skin(),
			'color'			=> $this->color(),
			'increase'		=> $this->increase()
		];
		
		return $attributes;
	}

	public function getParams()
	{
		return parent::getParams() + [
			'attributes'     => $this->attributes(),
		];
	}


}