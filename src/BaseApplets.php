<?php

namespace EasyApplets;


/**
 * base interface
 */
interface BaseApplets{

    public function GET($url,$clientConfig): array;

    public function POST($url,$clientConfig): array;

    public function PUT($url,$clientConfig): array;

    public function DELETE($url,$clientConfig);
}