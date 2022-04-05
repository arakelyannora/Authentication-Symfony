<?php

namespace App\Common;

use Symfony\Component\Uid\AbstractUid;

class Uuid implements UuidInterface
{
	private AbstractUid $abstractUid;

	public function __construct(AbstractUid $abstractUid)
	{
		$this->abstractUid = $abstractUid;
	}

	public function getUuidRfc4122(): string
	{
		return $this->abstractUid->toRfc4122();
	}

	public function toString(): string
	{
		return $this->getUuidRfc4122();
	}
}