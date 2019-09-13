<?php

namespace ProcessMonitor;

class Process
{
    public $user;
    public $pid;
    public $cpu;
    public $ram;
    public $vsz;
    public $rss;
    public $tty;
    public $stat;
    public $start;
    public $time;
    public $command;
    public $defunct;

    /**
     * @var PlatformInterface
     */
    private $driver;

    public function __construct($info)
    {
        $this->user = $info["user"];
        $this->pid = (int)$info["pid"];
        $this->cpu = (float)$info["cpu"];
        $this->ram = (float)$info["ram"];
        $this->vsz = (int)$info["vsz"];
        $this->rss = (int)$info["rss"];
        $this->tty = $info["tty"];
        $this->stat = $info["stat"];
        $this->start = $info["start"];
        $this->time = $info["time"];
        $this->command = $info["command"];

        $this->defunct = (bool)$info["defunct"];

        if (isset($info["debug"])) {
            $this->debug = (bool)$info["debug"];
        }

        if (empty($this->pid) || empty($this->command)) {
            throw new \Exception("Missing process id or command");
        }
    }

    /**
     * Kills this process
     */
    public function kill()
    {
        $this->driver->kill($this->pid);
    }

    /**
     * Gets all process childs
     *
     * @return array
     */
    public function getChilds()
    {
        return $this->driver->getChilds($this->pid);
    }

    /**
     * Kills all process childs
     */
    public function killChilds()
    {
        $childs = $this->getChilds();

        foreach ($childs as $child) {
            $child->kill();
        }
    }
}