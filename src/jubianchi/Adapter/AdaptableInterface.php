<?php
namespace jubianchi\Adapter;

use jubianchi\Adapter\AdapterInterface;

interface AdaptableInterface {
	/**
	 * @return \jubianchi\Adapter\AdapterInterface
	 */
	function getAdapter();

	/**
	 * @param \jubianchi\Adapter\AdapterInterface $adapter
	 * 
	 * @return AdapterInterface
	 */
	function setAdapter(AdapterInterface $adapter = null);
}