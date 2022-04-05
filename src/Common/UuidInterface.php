<?php

namespace App\Common;

interface UuidInterface
{
	public function getUuidRfc4122(): string;

	public function toString(): string;
}