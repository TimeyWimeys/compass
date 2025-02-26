/* jshint esversion: 6 */
/* jshint browser: true, devel: true */

import {registerBlockType} from '@wordpress/blocks';

import Edit from './edit';
import Save from './save';

registerBlockType(
	'compass/map',
	{
		edit: Edit,
		save: Save
	}
);