
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

//import './editor.scss';
import './style.scss';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
     const specs = useSelect( (select) => {
        return select('core/editor').getEditedPostAttribute('meta');
    }, []);
	return (
        <>
        <div { ...blockProps }>
           <RichText
                tagName="h2"
                value={ attributes.message }
                onChange={ ( val ) => setAttributes( { message: val ,} ) }                
            />
        </div>       
        </>

	);
}
