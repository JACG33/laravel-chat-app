/**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/#installation/NoRgTANARGB0DsCrQGwoCwpAZgJy/gA5cx1cBWeFMABnnhuOrypF0KPWSgFMBnZJFAQQEGmIkgAutADGAExTkAZrgBGUKUA=
 */

import { BalloonEditor, Autosave, Essentials, Paragraph, Bold, Link, Heading, Italic, List, Underline, BalloonToolbar } from '/ckeditor5/ckeditor5.js';

import translations from '/ckeditor5/translations/es.js';

/**
 * Create a free account with a trial: https://portal.ckeditor.com/checkout?plan=free
 */
const LICENSE_KEY = 'GPL'; // or <YOUR_LICENSE_KEY>.

const editorConfig = {
	toolbar: {
		items: ['heading', '|', 'bold', 'italic', 'underline', '|', 'link', '|', 'bulletedList', 'numberedList'],
		shouldNotGroupWhenFull: true
	},
	plugins: [Autosave, BalloonToolbar, Bold, Essentials, Heading, Italic, Link, List, Paragraph, Underline],
	balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
			{
				model: 'heading5',
				view: 'h5',
				title: 'Heading 5',
				class: 'ck-heading_heading5'
			},
			{
				model: 'heading6',
				view: 'h6',
				title: 'Heading 6',
				class: 'ck-heading_heading6'
			}
		]
	},
	language: 'es',
	licenseKey: LICENSE_KEY,
	link: {
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file',
					target: '_blank',
					rel: 'noopener noreferrer'
				}
			}
		}
	},
	translations: [translations]
};
document.addEventListener("DOMContentLoaded", () => {
	BalloonEditor.create(document.querySelector('#messageInput'), editorConfig).then(newEditor => {
		window.textEditor = newEditor
	});
})