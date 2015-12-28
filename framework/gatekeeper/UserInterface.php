<?php

namespace WernerDweight\Microbe\framework\gatekeeper;

interface UserInterface
{
	function getRole();
	function setRole($role);
}
