import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import './editor.scss';
import './style.scss';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps({
		className: 'worthio-product-hero-block',
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Hero Settings', 'worthio')} initialOpen={true}>
					<TextControl
						label={__('CTA in Edit Text', 'worthio')}
						value={attributes.ctaText}
						onChange={(value) => setAttributes({ ctaText: value })}
						 __next40pxDefaultSize={ true }
					/>
					<TextControl
						label={__('Custom CTA URL (optional)', 'worthio')}
						value={attributes.ctaUrl}
						onChange={(value) => setAttributes({ ctaUrl: value })}
						 __next40pxDefaultSize={ true }
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="hero-gallery">
					<div className="hero-gallery-main">
						<div className="hero-slide is-active hero-slide--empty" data-slide>
							<span>{__('Product image slider preview', 'worthio')}</span>
						</div>
					</div>
				</div>

				<div className="hero-content">
					<p className="hero-kicker">{__('Key Specifications', 'worthio')}</p>
					<h3 className="hero-title">{__('Product title from current post', 'worthio')}</h3>

					<div className="hero-price-panel">
						<div className="hero-score-card">
							<span className="hero-card-label">{__('Editorial Score', 'worthio')}</span>
							<strong>8.8/10</strong>
						</div>
						<div className="hero-price-card">
							<span className="hero-card-label">{__('Product Price', 'worthio')}</span>
							<strong>INR 74,999</strong>
						</div>
					</div>

					<div className="hero-specs-card">
						{[
							'Processor',
							'RAM/Storage',
							'Display',
							'Battery',
							'Front Camera',
							'Rear Camera',
							'Network',
							'OS',
						].map((label) => (
							<div className="hero-spec-row" key={label}>
								<span className="hero-spec-label">{__(label, 'worthio')}</span>
								<span className="hero-spec-value">
									{__('Pulled dynamically from product fields', 'worthio')}
								</span>
							</div>
						))}
					</div>

					<div className="hero-actions">
						<span className="hero-cta">{attributes.ctaText || __('Check Price', 'worthio')}</span>
					</div>
				</div>
			</div>
		</>
	);
}
