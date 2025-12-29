// public/js/admin-tinymce.js

const selector = 'textarea[data-editor="tinymce"], textarea.js-tinymce';

const getTargets = () => [...document.querySelectorAll(selector)];


const baseConfig = {
  base_url: '/vendor/tinymce',
  suffix: '.min',
  height: 360,
  menubar: false,
  branding: false,
  promotion: false,
  directionality: 'ltr',
  plugins: [
    'advlist',
    'autoresize',
    'charmap',
    'code',
    'directionality',
    'fontfamily',
    'fontsize',
    'fullscreen',
    'image',
    'link',
    'lists',
    'paste',
    'table',
    'wordcount',
  ],
  toolbar: [
    'undo redo | blocks | fontfamily fontsize | bold italic underline strikethrough',
    'alignleft aligncenter alignright alignjustify',
    'bullist numlist | outdent indent',
    'ltr rtl | link image table | charmap fullscreen | code removeformat',
  ],
  autoresize_bottom_margin: 20,
  autoresize_overflow_padding: 10,
  paste_data_images: false,
  paste_as_text: false,
  forced_root_block: 'p',
  formats: {
    alignjustify: { selector: 'p,div,li,td,th', styles: { textAlign: 'justify' } },
    alignleft: { selector: 'p,div,li,td,th', styles: { textAlign: 'left' } },
    aligncenter: { selector: 'p,div,li,td,th', styles: { textAlign: 'center' } },
    alignright: { selector: 'p,div,li,td,th', styles: { textAlign: 'right' } },
  },
    content_style: `
    body {
      font-size: 14px;
      line-height: 1.7;
      padding: 10px;
      text-align: justify;
    }

    p, div, li, td, th, blockquote {
      text-align: justify;
      text-justify: inter-word;
    }

    a { text-decoration: underline; }
    ul, ol { padding-inline-start: 1.25rem; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
    th { font-weight: 600; }
  `,
  fontsize_formats: '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 48px',
  font_family_formats:
    'Default=Inter,system-ui,sans-serif; Abyssinica SIL=Abyssinica SIL,serif; Nyala=Nyala,serif; Arial=Arial,Helvetica,sans-serif; Times New Roman="Times New Roman",Times,serif',
};

const initTinymceEditors = () => {
  const tinymce = window.tinymce;

  if (!tinymce) {
    console.error('[TinyMCE] global instance missing.');
    return;
  }

  const targets = getTargets();
  console.info('[TinyMCE] found', targets.length, 'textarea(s)');

  tinymce.remove(selector);

  if (!targets.length) {
    return;
  }

  const uploadUrl = document.body.dataset?.editorUploadUrl ?? '';
  const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

  const extraConfig = uploadUrl
    ? {
        images_upload_url: uploadUrl,
        automatic_uploads: true,
        images_reuse_filename: true,
        images_upload_credentials: true,
        headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
      }
    : {};

  targets.forEach((target) => {
    tinymce.init({
      target,
      ...baseConfig,
      ...extraConfig,
      setup(editor) {
        editor.on('change input undo redo', () => editor.save());
        editor.on('init', () => {
          try {
            editor.formatter.apply('alignjustify');
          } catch (error) {
            // ignore
          }
        });
      },
    });
  });
};

document.addEventListener('DOMContentLoaded', initTinymceEditors);
window.addEventListener('turbo:load', initTinymceEditors);
window.initTinyMCE = initTinymceEditors;
