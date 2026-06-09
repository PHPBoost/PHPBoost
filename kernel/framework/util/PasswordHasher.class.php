<?php
/**
 * Password hashing utility class that manages password hashing and verification.
 * Supports progressive migration from MD5/SHA256 to bcrypt.
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 05 02
*/

class PasswordHasher
{
	const BCRYPT_ALGO = PASSWORD_BCRYPT;
	const BCRYPT_COST = 12;
	const LEGACY_ALGO = 'sha256';

	/**
	 * Hash a password using bcrypt
	 * @param string $password The plain password
	 * @return string The bcrypt hash
	 */
	public static function hash(string $password): string
	{
		return password_hash($password, self::BCRYPT_ALGO, ['cost' => self::BCRYPT_COST]);
	}

	/**
	 * Check if a hash is in bcrypt format
	 * @param string $hash The hash to check
	 * @return bool True if the hash is bcrypt format
	 */
	public static function is_bcrypt_hash(string $hash): bool
	{
		return strpos($hash, '$2y$') === 0 || strpos($hash, '$2a$') === 0 || strpos($hash, '$2b$') === 0;
	}

	/**
	 * Verify a password against a bcrypt hash
	 * @param string $password The plain password
	 * @param string $hash The bcrypt hash
	 * @return bool True if the password matches
	 */
	public static function verify_bcrypt(string $password, string $hash): bool
	{
		return password_verify($password, $hash);
	}

	/**
	 * Verify a password against a legacy SHA256 hash
	 * @param string $password The plain password
	 * @param string $hash The SHA256 hash
	 * @return bool True if the password matches
	 */
	public static function verify_legacy(string $password, string $hash): bool
	{
		$legacy_hash = KeyGenerator::string_hash($password);
		return hash_equals($hash, $legacy_hash);
	}

	/**
	 * Verify a password against either bcrypt or legacy hash
	 * Returns a result array with verification status and migration flag
	 * @param string $password The plain password
	 * @param string $stored_hash The hash from database
	 * @return array Array with 'verified' (bool) and 'needs_migration' (bool)
	 */
	public static function verify(string $password, string $stored_hash): array
	{
		if (self::is_bcrypt_hash($stored_hash))
		{
			return [
				'verified' => self::verify_bcrypt($password, $stored_hash),
				'needs_migration' => false
			];
		}
		else
		{
			$verified = self::verify_legacy($password, $stored_hash);
			return [
				'verified' => $verified,
				'needs_migration' => $verified
			];
		}
	}
}
?>
