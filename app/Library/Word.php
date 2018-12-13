<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library;

use \App\Library\ServiceGenerateWord;
use \App\Library\WordServices\DatabaseGenerateWord;

class Word {

	public $word;
	public $description;

	public function __construct(ServiceGenerateWord $servicegenerateword) {
		return $servicegenerateword->getWord();
	}

}
