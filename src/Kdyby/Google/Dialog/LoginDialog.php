<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Google\Dialog;

use Nette\Http\UrlScript;



/**
 * @author Mikulas Dite <rullaf@gmail.com>
 * @author Filip Procházka <filip@prochazka.su>
 */
class LoginDialog extends AbstractDialog
{
	/** @var string */
	private $language;

	/**
	 * Checks, if there is a user in storage and if not, it redirects to login dialog.
	 * If the user is already in session storage, it will behave, as if were redirected from Google right now,
	 * this means, it will directly call onResponse event.
	 */
	public function handleOpen()
	{
		$this->open();
	}



	/**
	 * @return UrlScript
	 */
	public function getUrl()
	{
		$client = $this->google->client;
		/** @var \Google_Auth_OAuth2 $auth */
		$auth = $client->getAuth();

		// response signal url
		$client->setRedirectUri((string) $this->getReturnLink());

		// CSRF
		$this->session->establishCSRFTokenState();
		$auth->setState($this->session->state);

		$url = new UrlScript($this->google->client->createAuthUrl());
		
		if($this->language){
			$url->appendQuery(['hl' => $this->language]);
		}
		
		return $url;
	}
	
	
	/**
	 * @param string $language (cs, en, etc...)
	 */
	public function setLanguage($language)
	{
		$this->language = $language;
	}

}
