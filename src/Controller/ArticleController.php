<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
	/**
	 * @Route (
	 *     "/",
	 *     methods = {"GET"}
	 * )
	 */
	public function index() {
		return new Response('<html><body>hello</body></html>');
	}
}