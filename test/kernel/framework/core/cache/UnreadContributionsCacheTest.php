<?php

class UnreadContributionsCacheTest extends PHPBoostUnitTestCase
{
	public function __construct()
	{
	}
	
	public function test_are_there_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->set_admin_unread_contributions_number(2);
		self::assertEquals(true, $cache->are_there_unread_contributions());
	}

	public function test_set_admin_unread_contributions_number()
	{
		$cache = new UnreadContributionsCache();
		$cache->set_admin_unread_contributions_number(2);
		self::assertEquals(2, $cache->get_admin_unread_contributions_number());
	}

	public function test_have_moderators_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->set_moderators_have_unread_contributions(true);
		self::assertEquals(true, $cache->have_moderators_unread_contributions());
	}

	public function test_have_members_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->set_members_have_unread_contributions(true);
		self::assertEquals(true, $cache->have_members_unread_contributions());
	}

	public function test_get_groups_with_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->add_group_with_unread_contributions(2);
		self::assertContains(2, $cache->get_groups_with_unread_contributions());
		self::assertNotContains(1, $cache->get_groups_with_unread_contributions());
	}

	public function test_get_users_with_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->add_user_with_unread_contributions(5);
		self::assertContains(5, $cache->get_users_with_unread_contributions());
		self::assertNotContains(1, $cache->get_users_with_unread_contributions());
	}

	public function test_set_values()
	{
		$values = array (
			'r2' => 5,
			'r1' => 2,
			'r0' => 0,
			'g2' => 3,
			'g5' => 0,
			'm2' => 4,
			'm3' => 0
		);
		
		$cache = new UnreadContributionsCache();
		$cache->set_values($values);

		self::assertEquals(5, $cache->get_admin_unread_contributions_number());
		self::assertEquals(true, $cache->have_moderators_unread_contributions());
		self::assertEquals(false, $cache->have_members_unread_contributions());
		
		self::assertContains(2, $cache->get_groups_with_unread_contributions());
		self::assertNotContains(5, $cache->get_groups_with_unread_contributions());
		self::assertNotContains(1, $cache->get_groups_with_unread_contributions());
		
		self::assertContains(2, $cache->get_users_with_unread_contributions());
		self::assertNotContains(3, $cache->get_users_with_unread_contributions());
		self::assertNotContains(5, $cache->get_users_with_unread_contributions());
	}
	
	public function test_has_user_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->add_user_with_unread_contributions(5);
		self::assertEquals(true, $cache->has_user_unread_contributions(5));
		self::assertEquals(false, $cache->has_user_unread_contributions(3));
	}
	
	public function test_has_group_unread_contributions()
	{
		$cache = new UnreadContributionsCache();
		$cache->add_group_with_unread_contributions(5);
		self::assertEquals(true, $cache->has_group_unread_contributions(5));
		self::assertEquals(false, $cache->has_group_unread_contributions(3));
	}
}

?>