<?php

switch ($_sharer['type'])
{
	case 'facebook':
		echo '<a href="' . $_sharer['link'] . '" rel="external">Share on facebook</a>';
		break;

	case 'twitter':
		echo '<a href="' . $_sharer['link'] . '" rel="external">Share on twitter</a>';
		break;
}