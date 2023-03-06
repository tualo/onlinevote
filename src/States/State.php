<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;

interface State {
    public function transition(&$request,&$result):string;
}