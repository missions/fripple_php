<?php
class AppException extends Exception
{
}
class UserAlreadyExistsException extends AppException
{
}
class RecordNotFoundException extends AppException
{
}
class SessionAlreadyExistsException extends AppException
{
}
