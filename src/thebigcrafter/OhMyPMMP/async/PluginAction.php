<?php

/*
 * This file is part of oh-my-pmmp.
 * (c) thebigcrafter <hello.thebigcrafter@gmail.com>
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\OhMyPMMP\async;

use Closure;
use pocketmine\command\CommandSender;

abstract class PluginAction {

	protected CommandSender $sender;
	protected string $pluginName;
	protected bool $silent;
	protected ?Closure $onSuccess;
	protected ?Closure $onFail;

	public function __construct(CommandSender $sender, string $pluginName, bool $silent = false, ?Closure $onSuccess = null, ?Closure $onFail = null) {
		$this->sender = $sender;
		$this->pluginName = $pluginName;
		$this->silent = $silent;
		$this->onSuccess = $onSuccess;
		$this->onFail = $onFail;
	}

	public function getCommandSender() : CommandSender {
		return $this->sender;
	}

	public function getPluginName() : string {
		return $this->pluginName;
	}

	public function isSilent() : bool {
		return $this->silent;
	}

	public function onSuccess() : void {
		if($this->onSuccess !== null) {
			($this->onSuccess)();
		}
	}

	public function onFail() : void {
		if($this->onFail !== null) {
			($this->onFail)();
		}
	}

	abstract public function execute() : void;
}
