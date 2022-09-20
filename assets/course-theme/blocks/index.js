/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import courseNavigationBlock from './course-navigation';
import uiBlocks from './ui';
import lessonBlocks from './lesson-blocks';
import quizBlocks from './quiz-blocks';
import { templateStyleBlock } from './template-style';

const blocks = [
	...lessonBlocks,
	...quizBlocks,
	...uiBlocks,
	courseNavigationBlock,
	templateStyleBlock,
];

blocks.forEach( ( block ) => {
	const { name, ...settings } = block;
	registerBlockType( name, settings );
} );
