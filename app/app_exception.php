<?php
class AppException extends Exception
{
}

class InvalidArgumentException extends AppException
{
}

class UserAlreadyExistsException extends AppException
{
}

class UserBannedException extends AppException
{
}

class RecordNotFoundException extends AppException
{
}

class SessionAlreadyExistsException extends AppException
{
}

class SessionAlreadyExpiredException extends AppException
{
}