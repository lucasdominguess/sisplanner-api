<?php

namespace App\Interface;

interface LdapInterface
{
    public function loginLdap(string $username, string $password): array;
}
