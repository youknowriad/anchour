<?php
namespace jubianchi\Adapter\Test;

use jubianchi\Adapter\AdapterInterface as BaseAdapter;

use
    mageekguy\atoum,
    mageekguy\atoum\exceptions,
    mageekguy\atoum\test\adapter as AtoumAdapter
;

class Adapter extends AtoumAdapter implements BaseAdapter
{
    private $execStatus = null;

    public function setExecStatus($status)
    {
        $this->execStatus = $status;
    }

    public function exec($command, &$output = null, &$status = null)
    {
        $this->addCall('exec', func_get_args());

        $status = $this->execStatus;
    }
}
