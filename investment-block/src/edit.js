/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
const  {SelectControl, PanelBody, PanelRow} = wp.components;
const { serverSideRender: ServerSideRender } = wp;



/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
    const { attributes, setAttributes } = props;
    const [categories, setCategories] = useState([]);

    // Fetch the categories from the 'investment-category' taxonomy
    useEffect(() => {
        wp.apiFetch({
            path: '/wp/v2/investment-category', // Use the correct REST API path
        })
        .then((response) => {
            setCategories(response);
        })
        .catch((error) => {
            console.error(error);
        });
    }, []);

    const handleCategoryChange = (selected) => {
        setAttributes({ selectedCategories: selected });
    };

    const blockProps = useBlockProps();
	console.log("Attributes before rendering:", attributes);
    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Posts Settings')}>
                    <PanelRow>
                        <SelectControl
                            label={__('Categories:')}
                            multiple
                            value={attributes.selectedCategories}
                            options={categories.map((category) => ({
                                label: category.name,
                                value: category.id,
                            }))}
                            onChange={handleCategoryChange}
                        />
                    </PanelRow>

                </PanelBody>
            </InspectorControls>

            <ServerSideRender block="investment-block/investment-block" attributes={attributes.selectedCategories} />
        </div>
    );
}

