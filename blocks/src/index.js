import { registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import Save from './save';

registerBlockType( 
	'Compass/map',
	{
		edit: Edit,
		save: Save
	}
)