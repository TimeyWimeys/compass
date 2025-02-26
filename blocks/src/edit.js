/* jshint esversion: 6 */
/* jshint browser: true, devel: true */

import {__} from '@wordpress/i18n';

import {
    useBlockProps,
    InspectorControls
} from '@wordpress/block-editor';

import {
    Dashicon,
    TextControl,
    SelectControl,
    PanelBody,
    PanelRow,
    Button,
    ButtonGroup
} from '@wordpress/components';

export default function Edit(props) {
    const {
        attributes,
        setAttributes,
        className,
    } = props;

    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Custom Map Position', 'compass')} initialOpen={false}>
                    <PanelRow>
                        <p>{__('Feel free to customize initial map position (Latitude, Longitude, Zoom OR Region).', 'compass')}</p>
                    </PanelRow>
                    <PanelRow>
                        <p>{__('This will override the general configuration from the', 'compass')} <a
                            href="edit.php?post_type=oum-location&page=compass-settings">{__('settings', 'compass')}</a>.
                        </p>
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Latitude"
                            value={attributes.lat}
                            onChange={(val) =>
                                setAttributes({lat: val})}
                            placeholder="e.g. 51.50665732176545"
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Longitude"
                            value={attributes.long}
                            onChange={(val) =>
                                setAttributes({long: val})}
                            placeholder="e.g. -0.12752251529432854"
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Zoom (3-15)"
                            value={attributes.zoom}
                            onChange={(val) =>
                                setAttributes({zoom: val})}
                            placeholder="e.g. 13"
                        />
                    </PanelRow>
                    <PanelRow>
                        <p><strong>{__('OR', 'compass')}</strong></p>
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Pre-select region"
                            value={attributes.region}
                            onChange={(val) =>
                                setAttributes({region: val})}
                            placeholder="e.g. Europe"
                        />
                    </PanelRow>
                </PanelBody>
                <PanelBody title={__('Custom Style', 'compass')} initialOpen={false}>
                    <PanelRow>
                        <p>{__('This will override the general configuration from the', 'compass')} <a
                            href="edit.php?post_type=oum-location&page=compass-settings">{__('settings', 'compass')}</a>.
                        </p>
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label="Size"
                            value={attributes.size}
                            onChange={(val) =>
                                setAttributes({size: val})}
                            options={[
                                {label: '', value: ''},
                                {label: 'Content Width', value: 'default'},
                                {label: 'Full Width', value: 'fullwidth'},
                            ]}
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Height"
                            value={attributes.height}
                            onChange={(val) =>
                                setAttributes({height: val})}
                            placeholder="e.g. 400px"
                            help={__('Don\'t forget to add a unit like px.', 'compass')}
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Height (mobile)"
                            value={attributes.height_mobile}
                            onChange={(val) =>
                                setAttributes({height_mobile: val})}
                            placeholder="e.g. 400px"
                            help={__('Don\'t forget to add a unit like px.', 'compass')}
                        />
                    </PanelRow>
                </PanelBody>
                <PanelBody title={__('Filter ', 'compass')} initialOpen={false}>
                    <PanelRow>
                        <p>{__('Show only specific markers by filtering for categories or Post IDs. You can separate multiple Categories or IDs with a | symbol.', 'compass')}</p>
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Filter by Marker Categories"
                            value={attributes.types}
                            onChange={(val) =>
                                setAttributes({types: val})}
                            placeholder="e.g. food|drinks"
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Filter by Post IDs"
                            value={attributes.ids}
                            onChange={(val) =>
                                setAttributes({ids: val})}
                            placeholder="e.g. 1|2|3"
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div class="hint">
                    <h5>{__('Open User Map', 'compass')}</h5>
                    <p>
                        {__('This block will show your location markers on a map in the front end.', 'compass')}
                    </p>
                    <Button
                        icon="location-alt"
                        variant="primary"
                        onClick={() => window.location.href = 'edit.php?post_type=oum-location'}
                    >
                        {__('Manage Locations', 'compass')}
                    </Button>
                    <Button
                        icon="admin-settings"
                        variant="secondary"
                        onClick={() => window.location.href = 'edit.php?post_type=oum-location&page=compass-settings'}
                    >
                        {__('Map Settings', 'compass')}
                    </Button>
                </div>
            </div>
        </>
    )
}