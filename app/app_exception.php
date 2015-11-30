<?php
class AppException extends Exception
{
}
class UserAlreadyExistsException extends AppException
{
}
class UserAlreadyConnectedToFacebookException extends AppException
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
class SessionNotActiveException extends AppException
{
}
class FacebookIdAlreadyExistsException extends AppException
{
}
class UserRoleNotNormalException extends AppException
{
}
class UserRoleNotRestaurantAdminException extends AppException
{
}
class UserAlreadyFollowingException extends AppException
{
}
class RestaurantNameAlreadyExistsException extends AppException
{
}