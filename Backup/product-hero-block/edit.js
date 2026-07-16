import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import './editor.scss';
import './style.scss';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Hero Settings', 'worthio')} initialOpen={true}>
					<TextControl
						label={__('CTA Text', 'worthio')}
						value={attributes.ctaText}
						onChange={(value) => setAttributes({ ctaText: value })}
					/>
					<TextControl
						label={__('Custom CTA URL (optional)', 'worthio')}
						value={attributes.ctaUrl}
						onChange={(value) => setAttributes({ ctaUrl: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<strong>{__('Product Hero Block', 'worthio')}</strong>
				<p>
					{__(
						'Dynamic block: Product Name, Featured Image, Editorial Score, Price, CTA, and Key Specs are pulled from post data.',
						'worthio'
					)}
				</p>
			</div>
		</>
	);
}

