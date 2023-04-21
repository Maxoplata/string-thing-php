# StringThing

StringThing is a lightweight library for encoding and decoding strings using various patterns.

## Installation

```sh
compsoer require string-thing
```

## Usage

StringThing provides an API for encoding and decoding strings. To use it, import the StringThing class and create a new instance with an array of patterns in the order you want to use them:

```php
use Maxoplata\StringThing;

$myString = 'This is my string';

// Create a new instance of StringThing with default pattern (['split-halves', 'reverse', 'shift', 'swap-case', 'rotate'])
$myStringThing = new StringThing();

// Encode the string
$encoded = $myStringThing->encode($myString);

// Output the encoded string
print encoded; // "ZN!TJ!TJIuHOJSUT!"

// Decode the string
$decoded = myStringThing->decode($encoded);

// Output the decoded string
print decoded; // "This is my string"
```

## Patterns

StringThing patterns currently support the following operations:

- `split-halves`: Splits the string into two halves and swaps them.
	- `Abcd12` => `d12Abc`
- `reverse`: Reverses the order of the characters in the string.
	- `Abcd12` => `21dcbA`
- `shift`: Shifts the characters in the string up by 1 in the ASCII table.
	- `Abcd12` => `Bcde23`
- `swap-case`: Swaps uppercase & lowercase characters in the string.
	- `Abcd12` => `aBCD12`
- `rotate`:  Shifts the string 1 position to the right.
	- `Abcd12` => `2Abcd1`

To use a specific pattern, pass it as an argument to the StringThing constructor:

```php
use Maxoplata\StringThing;

$myStringThing1 = new StringThing(['split-halves', 'shift', 'reverse', 'shift', 'swap-case', 'rotate']);

// OR

$stringThingPattern = ['split-halves', 'shift', 'reverse', 'shift', 'swap-case', 'rotate'];
$myStringThing2 = new StringThing($stringThingPattern);
```

## Example: Encoding Passwords for Secure Storage

StringThing can be used to encode passwords before hashing them and storing them in a database, making it more difficult for an attacker to retrieve the original password even if they gain access to the database.

Here's an example of how to use StringThing to encode a password before hashing it with bcrypt when working with passwords in a database:

#### Create User:

```php
use Maxoplata\StringThing;

$stringThingPattern = ['split-halves', 'shift', 'reverse', 'shift', 'swap-case', 'rotate'];

// The original password to be encoded and hashed
$password = 'myPassword123';

// Encode the password using StringThing
$encodedPassword = (new StringThing($stringThingPattern))->encode($password);

// Hash the encoded password with bcrypt
$hashedPassword = password_hash($encodedPassword, PASSWORD_BCRYPT);

// Add the hashed password to a user object for storage in a database
$user = [
  'username' => 'johndoe',
  'email' => 'johndoe@example.com',
  'password' => hashedPassword,
  // other user data...
];

// Add the user object to the database
myDatabase->addUser($user);
```

#### Authenticate User:

```php
use Maxoplata\StringThing;

$stringThingPattern = ['split-halves', 'shift', 'reverse', 'shift', 'swap-case', 'rotate'];

// Retrieve the user's hashed password and salt from the database
$user = $myDatabase->getUserByUsername('johndoe');
$hashedPassword = $user->password;

// The password entered by the user attempting to log in
$passwordAttempt = 'myPassword123';

// Encode the password attempt using StringThing
$encodedPasswordAttempt = (new StringThing($stringThingPattern))->encode($passwordAttempt);

// Hash the encoded password attempt with bcrypt
$hashedPasswordAttempt = password_hash($encodedPassword, PASSWORD_BCRYPT);

// Compare the hashed password attempt to the stored hashed password
if ($hashedPasswordAttempt === $hashedPassword) {
  // Passwords match - login successful!
} else {
  // Passwords do not match - login failed
}
```
