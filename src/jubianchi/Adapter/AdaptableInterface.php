<?php
namespace jubianchi\Adapter;

use jubianchi\Adapter\AdapterInterface;

interface AdaptableInterface
{
    /**
     * @return \jubianchi\Adapter\AdapterInterface
     */
    public function getAdapter();

    /**
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     *
     * @return AdapterInterface
     */
    public function setAdapter(AdapterInterface $adapter = null);
}
