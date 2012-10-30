<?php


/**
 * Describes organizational groups
 *
 * @author Chris Conover
 **/
class OrganizationalGroups extends CustomTaxonomy
{
	public
		$name               = 'org_groups',
		$general_name       = 'Organizational Groups',
		$singular_name      = 'Organizational Group',
		$search_items       = 'Search Organizational Groups',
		$popular_items      = 'Popular Organizational Groups',
		$all_times          = 'All Organizational Groups',
		$parent_item        = 'Parent Organizational Group',
		$parent_item_colon  = 'Parent Organizational Group:',
		$edit_item          = 'Edit Organizational Group',
		$update_item        = 'Update Organizational Group',
		$add_new_item       = 'Add New Organizational Group',
		$new_item_name      = 'New Tag Organizational Group',
		
		$hierarchical = True;
} // END class 

?>