DELIMITER  //
set FOREIGN_KEY_CHECKS=0//
alter table ds_renderer_stylesheet modify classname varchar(150)//
alter table ds_renderer_stylesheet_attributes modify classname varchar(150)//
set FOREIGN_KEY_CHECKS=1//



INSERT IGNORE INTO
    `ds_renderer_stylesheet_groups` (`id`, `name`)
VALUES
    (5, 'ballotpaper'),
    (11, 'checkbox'),
    (8, 'contact-bubble'),
    (2, 'FOOT'),
    (12, 'ihk-default-theme'),
    (1, 'LETTER-ISO-5008'),
    (6, 'menu-toggle'),
    (9, 'muc21'),
    (7, 'style2021muc'),
    (3, 'TABLE'),
    (10, 'wms'),
    (4, 'wmsite')//

    

INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('address', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('pagebackground', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('pagecontent', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('pageinfo', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('pagemargins', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('senderaddress', 1)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('footerinfoblock', 2)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('footerinfoblocklabel', 2)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('footerinfoblockvalue', 2)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('anzeige_name', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidatetable', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hr_notvoted', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('number', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('rank', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('td_notvoted', 3)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('abel-header', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('background-image', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('background-image:after', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('ballotpaper-hint', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-dark', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-light', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-darkx', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-midx', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('branding', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('branding a img', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-logout', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidate-check', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidate-line', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidate-portrait', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('claim', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('claimparagraph', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('color-white', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('debugborder', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('gradient-dl', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('gradient-ld', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('header-topbar', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('headerbanner', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('headerbanner_green', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hg-line', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('lines-button', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('logout', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('menu-line', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('menu-toggle', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('menu-toggle:before', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('midtext-size', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('nav-item', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('navbar', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('nowrap', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('step', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('stepparagraph', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('sz-header-text', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('szg-header-sitze', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('szg-header-text', 4)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-dark', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-dark-disabled', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-light', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-light-disabled', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-lightgray', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-mid', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-bg-mid-disabled', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-border-none', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-btn-border', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-btn-button', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-btn-filled', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-btn-large', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-btn-link', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-black', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-dark', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-green', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-red', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-white', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-large', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-large > h1,h2', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-normal', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-small', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-xlarge', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-xlarge >  h3', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-xlarge > h1,h2', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-xlarge > h4', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fs-xxlarge', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-fw-bold', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-paragraph-li', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-paragraph-li-nr', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-paragraph-li-span', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-paragraph-ul', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-underline-border', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-x-img', 5)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-blue', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-blue > .container > .row > .col > p', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-darkgrey', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-gray', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-green', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-lightblue', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bg-color-white', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-blue-light', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-gray', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-link-white-blue', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-link-white-blue:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-link-white-white', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-link-white-white:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bt > h3', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bt>p', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bt>p:nth-child(2) > strong:nth-child(1)', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bt>p:nth-child(2) > strong:nth-child(2)', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bt>p:nth-child(3)', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-green-white', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-green-white:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-red-white', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-red-white:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-white-blue', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-white-blue:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-white-green', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-color-white-green:hover', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-logout', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-margin-left', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidate-content > div > span > strong', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('candidate-content-tr', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('con-img > p > img', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('dimg > p > img', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('footer', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('header-bar', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('header-vh-4 > h3', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('left-linklist li  a', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('left-linklist li  a:before', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('left-linklist li a:after', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('legitimation > ul', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('mid-linklist li  a', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc-page', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('sec > p:nth-child(4) > em', 7)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('contact-bubble', 8)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('contact-bubble-text', 8)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('contact-bubble-text:focus', 8)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('contact-bubble-text:hover', 8)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('contact-bubble:hover ~ div.contact-bubble-text', 8)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-blue', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-color-white > p > a, .bp-color-white > a', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('bp-lh-large > img', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs > h1', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs > h2', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs > h3', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs > h4', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs-l', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs-n', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs-n-fixed', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs-s', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-bp-fs-xl', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-pb-n', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-pb-xs', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-pr-xs', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-pt-n', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('muc21-pt-xs', 9)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES (':root', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('btn-primary', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('buttons', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('claim', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('column-bgkl', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('form-control', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-ballotpaper .candidate-portrait, .page-ballotpaper .picture', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-ballotpaper > div > div:nth-child(2) > table > thead > tr > th.check', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-ballotpaper > div > div:nth-child(2) > table > thead > tr > th.check.align-middle > div', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-ballotpaper p', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-ballotpaper p.person1', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-chooseballotpaper.container-fluid', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-chooseballotpaper.row', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-claim', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-claim.container-fluid', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-footer', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-footer .mid-linklist li a', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header > div > div > * > a > img', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header > div > div > div.col.text-end > a', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header > div > div > div.text-end > div > div > a > i', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header > div > div > div.text-end > div > div > a > i > span', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-header > div > div > div:nth-child(2) > a > img', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-legitimation-subtext', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-legitimation-subtext > div.container', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-login > div > div:nth-child(2)', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-logout.container-fluid > div > * > div > a', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('text-contour', 10)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('circle-checkbox[type=\"checkbox\"]', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('circle-checkbox[type=\"checkbox\"] + label div', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('circle-checkbox[type=\"checkbox\"] + label div i', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('circle-checkbox[type=\"checkbox\"]:checked + label div i', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('circle-checkbox[type=\"checkbox\"]:checked:disabled + label div i', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"]', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1) i', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"]:checked + label div div:nth-child(1) i', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"]:checked~.formbox', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('hint-circle-checkbox[type=\"checkbox\"]:not(:checked)~.formbox', 11)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES (':root', 12)//
INSERT IGNORE INTO ds_renderer_stylesheet (classname, `group`) VALUES ('page-footer', 12)//



INSERT IGNORE INTO `ds_renderer_stylesheet_attributes` VALUES
(':root ','--bs-black','#000','\'',12),
(':root ','--bs-black-rgb','0,0,0','\'',12),
(':root ','--bs-blue','#0d6efd','\'',12),
(':root ','--bs-body-bg','#fff','\'',12),
(':root ','--bs-body-bg-rgb','255,255,255','\'',12),
(':root ','--bs-body-color','#212529','\'',12),
(':root ','--bs-body-color-rgb','33,37,41','\'',12),
(':root ','--bs-body-font-family','var(--bs-font-sans-serif)','\'',12),
(':root ','--bs-body-font-size','1rem','\'',12),
(':root ','--bs-body-font-weight','400','\'',12),
(':root ','--bs-body-line-height','1.5','\'',12),
(':root ','--bs-border-color','var(--bs-light-text)','\'',12),
(':root ','--bs-border-color-translucent','rgba(0, 0, 0, 0.175)','\'',12),
(':root ','--bs-border-radius','0.375rem','\'',12),
(':root ','--bs-border-radius-2xl','2rem','\'',12),
(':root ','--bs-border-radius-lg','0.5rem','\'',12),
(':root ','--bs-border-radius-pill','50rem','\'',12),
(':root ','--bs-border-radius-sm','0.25rem','\'',12),
(':root ','--bs-border-radius-xl','1rem','\'',12),
(':root ','--bs-border-style','solid','\'',12),
(':root ','--bs-border-width','1px','\'',12),
(':root ','--bs-box-shadow','0 0.5rem 1rem rgba(var(--bs-body-color-rgb), 0.15)','\'',12),
(':root ','--bs-box-shadow-inset','inset 0 1px 2px rgba(var(--bs-body-color-rgb), 0.075)','\'',12),
(':root ','--bs-box-shadow-lg','0 1rem 3rem rgba(var(--bs-body-color-rgb), 0.175)','\'',12),
(':root ','--bs-box-shadow-sm','0 0.125rem 0.25rem rgba(var(--bs-body-color-rgb), 0.075)','\'',12),
(':root ','--bs-breakpoint-lg','992px','\'',12),
(':root ','--bs-breakpoint-md','768px','\'',12),
(':root ','--bs-breakpoint-sm','576px','\'',12),
(':root ','--bs-breakpoint-xl','1200px','\'',12),
(':root ','--bs-breakpoint-xs','0','\'',12),
(':root ','--bs-breakpoint-xxl','1400px','\'',12),
(':root','--bs-btn-bg','rgb(1,51,102)','',0),
(':root ','--bs-code-color','#d63384','\'',12),
(':root ','--bs-cyan','#0dcaf0','\'',12),
(':root ','--bs-danger','#dc3545','\'',12),
(':root ','--bs-danger-bg-subtle','#f8d7da','\'',12),
(':root ','--bs-danger-border-subtle','#f1aeb5','\'',12),
(':root ','--bs-danger-rgb','220,53,69','\'',12),
(':root ','--bs-danger-text','#b02a37','\'',12),
(':root ','--bs-dark','#212529','\'',12),
(':root ','--bs-dark-bg-subtle','#ced4da','\'',12),
(':root ','--bs-dark-border-subtle','#adb5bd','\'',12),
(':root ','--bs-dark-rgb','33,37,41','\'',12),
(':root ','--bs-dark-text','#495057','\'',12),
(':root ','--bs-emphasis-color','#000','\'',12),
(':root ','--bs-emphasis-color-rgb','0,0,0','\'',12),
(':root ','--bs-font-monospace','SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace','\'',12),
(':root ','--bs-font-sans-serif','system-ui,-apple-system,Segoe UI,Roboto,Helvetica Neue,Noto Sans,Liberation Sans,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji','\'',12),
(':root ','--bs-form-control-bg','var(--bs-body-bg)','\'',12),
(':root ','--bs-form-control-disabled-bg','var(--bs-secondary-bg)','\'',12),
(':root ','--bs-gradient','linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0))','\'',12),
(':root ','--bs-gray','#6c757d','\'',12),
(':root ','--bs-gray-100','#f8f9fa','\'',12),
(':root ','--bs-gray-200','#e9ecef','\'',12),
(':root ','--bs-gray-300','#dee2e6','\'',12),
(':root ','--bs-gray-400','#ced4da','\'',12),
(':root ','--bs-gray-500','#adb5bd','\'',12),
(':root ','--bs-gray-600','#6c757d','\'',12),
(':root ','--bs-gray-700','#495057','\'',12),
(':root ','--bs-gray-800','#343a40','\'',12),
(':root ','--bs-gray-900','#212529','\'',12),
(':root ','--bs-gray-dark','#343a40','\'',12),
(':root ','--bs-green','#198754','\'',12),
(':root ','--bs-highlight-bg','#fff3cd','\'',12),
(':root ','--bs-indigo','#6610f2','\'',12),
(':root ','--bs-info','#0dcaf0','\'',12),
(':root ','--bs-info-bg-subtle','#cff4fc','\'',12),
(':root ','--bs-info-border-subtle','#9eeaf9','\'',12),
(':root ','--bs-info-rgb','13,202,240','\'',12),
(':root ','--bs-info-text','#087990','\'',12),
(':root ','--bs-light','#f8f9fa','\'',12),
(':root ','--bs-light-bg-subtle','#fcfcfd','\'',12),
(':root ','--bs-light-border-subtle','#e9ecef','\'',12),
(':root ','--bs-light-rgb','248,249,250','\'',12),
(':root ','--bs-light-text','#6c757d','\'',12),
(':root ','--bs-link-color','#0d6efd','\'',12),
(':root ','--bs-link-color-rgb','13,110,253','\'',12),
(':root ','--bs-link-decoration','underline','\'',12),
(':root ','--bs-link-hover-color','#0a58ca','\'',12),
(':root ','--bs-link-hover-color-rgb','10,88,202','\'',12),
(':root ','--bs-orange','#fd7e14','\'',12),
(':root ','--bs-pink','#d63384','\'',12),
(':root ','--bs-primary','rgb(1,51,102)','\'',12),
(':root ','--bs-primary-bg-subtle','#cfe2ff','\'',12),
(':root ','--bs-primary-border-subtle','#9ec5fe','\'',12),
(':root ','--bs-primary-rgb','13,110,253','\'',12),
(':root ','--bs-primary-text','#0a58ca','\'',12),
(':root ','--bs-purple','#6f42c1','\'',12),
(':root ','--bs-red','#dc3545','\'',12),
(':root ','--bs-secondary','#6c757d','\'',12),
(':root ','--bs-secondary-bg','#e9ecef','\'',12),
(':root ','--bs-secondary-bg-rgb','233,236,239','\'',12),
(':root ','--bs-secondary-bg-subtle','#f8f9fa','\'',12),
(':root ','--bs-secondary-border-subtle','#e9ecef','\'',12),
(':root ','--bs-secondary-color','rgba(33, 37, 41, 0.75)','\'',12),
(':root ','--bs-secondary-color-rgb','33,37,41','\'',12),
(':root ','--bs-secondary-rgb','108,117,125','\'',12),
(':root ','--bs-secondary-text','#6c757d','\'',12),
(':root ','--bs-success','#198754','\'',12),
(':root ','--bs-success-bg-subtle','#d1e7dd','\'',12),
(':root ','--bs-success-border-subtle','#a3cfbb','\'',12),
(':root ','--bs-success-rgb','25,135,84','\'',12),
(':root ','--bs-success-text','#146c43','\'',12),
(':root ','--bs-teal','#20c997','\'',12),
(':root ','--bs-tertiary-bg','#f8f9fa','\'',12),
(':root ','--bs-tertiary-bg-rgb','248,249,250','\'',12),
(':root ','--bs-tertiary-color','rgba(33, 37, 41, 0.5)','\'',12),
(':root ','--bs-tertiary-color-rgb','33,37,41','\'',12),
(':root ','--bs-warning','#ffc107','\'',12),
(':root ','--bs-warning-bg-subtle','#fff3cd','\'',12),
(':root ','--bs-warning-border-subtle','#ffe69c','\'',12),
(':root ','--bs-warning-rgb','255,193,7','\'',12),
(':root ','--bs-warning-text','#997404','\'',12),
(':root ','--bs-white','#fff','\'',12),
(':root ','--bs-white-rgb','255,255,255','\'',12),
(':root ','--bs-yellow','#ffc107','\'',12),
(':root','--container-fluid-margin-top','1em','',0),
(':root','--danger','#f00;','',12),
(':root','--page-claim-bg','rgb(1,51,102)','',0),
(':root','--page-claim-color','rgb(255,255,255)','',0),
(':root','--page-footer-bg','rgb(1,51,102)','',0),
(':root','--page-footer-color','rgb(255,255,255)','',0),
('abel-header','font-family','Abel','',4),
('abel-header','font-size','37pt','',4),
('address','font-size','1em','',1),
('address','font-weight','normal','',1),
('address','height','27.3mm','',1),
('address','left','25mm','',1),
('address','overflow','hidden','',1),
('address','padding-left','0mm','',1),
('address','padding-top','0mm','',1),
('address','position','fixed','',1),
('address','top','17.5mm','',1),
('address','width','80mm','',1),
('anzeige_name','font-size','1.0em','',3),
('background-image','height','100%;','',4),
('background-image','left','0px;','',4),
('background-image','position','fixed;','',4),
('background-image','top','0px;','',4),
('background-image','width','100%;','',4),
('background-image','z-index','0;','',4),
('background-image:after','  background','transparent url(/images/slider_frau_ret.jpg) repeat top left;','',4),
('background-image:after','content','\'\';','',4),
('ballotpaper-hint','line-height','38pt','',4),
('bg-color-blue','background-color','rgb(1,158,212);','',7),
('bg-color-blue > .container > .row > .col > p','color','white;','',7),
('bg-color-dark','background-color','rgb(4, 79, 146);','',4),
('bg-color-darkgrey','background-color','rgb(234,234,234);','',7),
('bg-color-gray','background-color','rgb(244,244,244);','',7),
('bg-color-green','background-color','rgb(122,180,30);','',7),
('bg-color-light','background-color','rgb(0, 52, 102);','',4),
('bg-color-lightblue','background-color','rgba(1,158,212,0.5);','',7),
('bg-color-white','background-color','rgb(255,255,255);','',7),
('bp-bg-dark','background-color','#003365;','',5),
('bp-bg-dark-disabled','background-color','#aaa','',5),
('bp-bg-darkx','background-color','#003365;','',4),
('bp-bg-light','background-color','#D5DFE9;','',5),
('bp-bg-light-disabled','background-color','#eee;','',5),
('bp-bg-lightgray','background-color','rgb(220,220,220);','',5),
('bp-bg-mid','background-color','#2D75B1;','',5),
('bp-bg-mid-disabled','background-color','#ddd;','',5),
('bp-bg-midx','background-color','#2D75B1;','',4),
('bp-border-none','border','none','',5),
('bp-btn-border','background-color','white;','',5),
('bp-btn-border','border','solid 2px rgb(175,204,122);','',5),
('bp-btn-border','border-radius','6%/18%;','',5),
('bp-btn-button','padding-top','10px','',5),
('bp-btn-filled','background-color','rgb(175,204,122);','',5),
('bp-btn-filled','border','0px;','',5),
('bp-btn-filled','border-radius','6%/18%;','',5),
('bp-btn-large','height','50px;','',5),
('bp-btn-large','min-width','180px;','',5),
('bp-btn-link','padding-top','11px','',5),
('bp-color-black','color','#000;','',5),
('bp-color-blue','color','rgb(0,52,102)','',9),
('bp-color-blue-light','color','#044f92;','',7),
('bp-color-dark','color','rgb(0,73,136);','',5),
('bp-color-gray','color','rgb(133,134,140);','',7),
('bp-color-green','color','rgb(175,204,122);','',5),
('bp-color-red','color','#E3000F;','',5),
('bp-color-white','color','#fff;','',5),
('bp-color-white > p > a, .bp-color-white > a','color','white;','',9),
('bp-color-white > p > a, .bp-color-white > a','text-decoration','underline;','',9),
('bp-fs-large','font-size','max(0.9vw,18px)','',5),
('bp-fs-large > h1,h2','font-size','max(1.1vw, 18px);','',5),
('bp-fs-large > h1,h2','font-weight','600','',5),
('bp-fs-large > h1,h2','line-height','max(1.3vw, 18px);','',5),
('bp-fs-normal','font-size','1.0rem;','',5),
('bp-fs-small','font-size','0.8rem','',5),
('bp-fs-xlarge','font-size','max(1.2vw,18px);','',5),
('bp-fs-xlarge','font-weight','600','',5),
('bp-fs-xlarge','line-height','max(1.3vw,20px);','',5),
('bp-fs-xlarge >  h3','font-size','max(1.2vw, 18px);','',5),
('bp-fs-xlarge >  h3','font-weight','600;','',5),
('bp-fs-xlarge >  h3','line-height','max(2vw, 22px);','',5),
('bp-fs-xlarge > h1,h2','font-size','max(1.6vw, 22px);','',5),
('bp-fs-xlarge > h1,h2','font-weight','600;','',5),
('bp-fs-xlarge > h1,h2','line-height','max(2.2vw, 24px);','',5),
('bp-fs-xlarge > h4','font-size','max(1.8vw, 20px);','',5),
('bp-fs-xlarge > h4','line-height','max(20vw, 22px);','',5),
('bp-fs-xxlarge','font-size','2rem','',5),
('bp-fw-bold','font-weight','bold','',5),
('bp-lh-large > img','width','100%;','',9),
('bp-link-white-blue','color','rgb(255,255,255);','',7),
('bp-link-white-blue:hover','color','rgb(4,79,146);','',7),
('bp-link-white-white','color','white;','',7),
('bp-link-white-white','font-weight','bold;','',7),
('bp-link-white-white:hover','color','white;','',7),
('bp-link-white-white:hover','font-weight','bold;','',7),
('bp-paragraph-li','padding-left','15px','',5),
('bp-paragraph-li-nr','margin-left','-25px','',5),
('bp-paragraph-li-nr','position','absolute','',5),
('bp-paragraph-li-span','padding-left','0px','',5),
('bp-paragraph-ul','list-style-type','none','',5),
('bp-paragraph-ul','padding-left','10px;','',5),
('bp-underline-border','border','none','',5),
('bp-underline-border','border-bottom','1px solid #DFE3E9;','',5),
('bp-x-img','background-clip','padding-box;','',5),
('bp-x-img','background-image','url(/images/a.svg);','',5),
('bp-x-img','background-position','center bottom;','',5),
('bp-x-img','background-repeat','no-repeat;','',5),
('bp-x-img','text-align','center;','',5),
('bp-x-img','vertical-align','middle;','',5),
('branding','padding','15px;','',4),
('branding a img','height','auto;','',4),
('branding a img','padding','0px;','',4),
('branding a img','width','250px!important;','',4),
('bt > h3','font-size','max(1.2vw,18px);','',7),
('bt > h3','margin-bottom','0px;','',7),
('bt > h3','margin-top','25px;','',7),
('bt>p','line-height','max(1.5vw,24px);','',7),
('bt>p','margin-bottom','0px;','',7),
('bt>p:nth-child(2) > strong:nth-child(1)','background-color','rgb(159,80,152);','',7),
('bt>p:nth-child(2) > strong:nth-child(1)','padding-left','5px;','',7),
('bt>p:nth-child(2) > strong:nth-child(1)','padding-right','5px;','',7),
('bt>p:nth-child(2) > strong:nth-child(2)','background-color','rgb(240,130,26);','',7),
('bt>p:nth-child(2) > strong:nth-child(2)','padding-left','5px;','',7),
('bt>p:nth-child(2) > strong:nth-child(2)','padding-right','5px;','',7),
('bt>p:nth-child(3)','padding-top','10px;','',7),
('btn-color-green-white','background-color','rgb(122,180,30)','',7),
('btn-color-green-white','border','1px solid rgb(122,180,30);','',7),
('btn-color-green-white','border-radius','3px;','',7),
('btn-color-green-white','color','white;','',7),
('btn-color-green-white','padding-bottom','8px;','',7),
('btn-color-green-white','padding-left','18px;','',7),
('btn-color-green-white','padding-right','18px;','',7),
('btn-color-green-white','padding-top','8px;','',7),
('btn-color-green-white','text-decoration','none!important;','',7),
('btn-color-green-white:hover','background-color','rgba(122,180,30,0.5);','',7),
('btn-color-green-white:hover','color','white;','',7),
('btn-color-green-white:hover','text-decoration','none;','',7),
('btn-color-logout','background-color','darkgrey','',4),
('btn-color-logout','border-radius','3px;','',4),
('btn-color-logout','color','white','',4),
('btn-color-logout','padding-bottom','8px;','',4),
('btn-color-logout','padding-left','18px;','',4),
('btn-color-logout','padding-right','18px;','',4),
('btn-color-logout','padding-top','8px;','',4),
('btn-color-red-white','background-color','rgb(157,157,157);','',7),
('btn-color-red-white','border','1px solid rgb(157,157,157);','',7),
('btn-color-red-white','border-radius','3px;','',7),
('btn-color-red-white','color','white!important;','',7),
('btn-color-red-white','padding-bottom','8px;','',7),
('btn-color-red-white','padding-left','18px;','',7),
('btn-color-red-white','padding-right','18px;','',7),
('btn-color-red-white','padding-top','8px;','',7),
('btn-color-red-white','text-decoration','none!important;','',7),
('btn-color-red-white:hover','background-color','rgba(200,200,200);','',7),
('btn-color-white-blue','background-color','rgb(239,240,239)','',7),
('btn-color-white-blue','border','1px solid rgb(1,158,212);','',7),
('btn-color-white-blue','border-radius','3px;','',7),
('btn-color-white-blue','color','rgb(1,158,212);','',7),
('btn-color-white-blue','padding-bottom','0px;','',7),
('btn-color-white-blue','padding-left','18px;','',7),
('btn-color-white-blue','padding-right','18px;','',7),
('btn-color-white-blue','padding-top','0px;','',7),
('btn-color-white-blue:hover','background-color','rgb(4,79,146);','',7),
('btn-color-white-blue:hover','color','rgb(239,240,239);','',7),
('btn-color-white-green','background-color','rgb(255,255,255)','',7),
('btn-color-white-green','border','solid 1px rgb(122,180,30)','',7),
('btn-color-white-green','border-radius','3px','',7),
('btn-color-white-green','color','rgb(122,180,30)','',7),
('btn-color-white-green','padding-bottom','8px','',7),
('btn-color-white-green','padding-left','18px','',7),
('btn-color-white-green','padding-right','18px','',7),
('btn-color-white-green','padding-top','8px','',7),
('btn-color-white-green:hover','background-color','rgb(240,240,240)','',7),
('btn-color-white-green:hover','color','rgb(122,180,30)','',7),
('btn-color-white-green:hover','text-decoration','none','',7),
('btn-logout','border','0px;','',7),
('btn-logout','border-radius','5px;','',7),
('btn-logout','color','white!important;','',7),
('btn-logout','font-size','18px;','',7),
('btn-logout','padding-bottom','6px;','',7),
('btn-logout','padding-left','12px;','',7),
('btn-logout','padding-right','12px;','',7),
('btn-logout','padding-top','6px;','',7),
('btn-logout','text-decoration','none!important;','',7),
('btn-margin-left','margin-left','18px;','',7),
('btn-primary','--bs-btn-bg','var(--page-claim-bg)','',0),
('btn-primary','--bs-btn-border-color','var(--page-claim-bg)','',0),
('btn-primary','--bs-btn-hover-bg','#ccd7e6','',0),
('btn-primary','--bs-btn-hover-border-color','#ccd7e6','',0),
('buttons','margin-top','var(--container-fluid-margin-top)','',0),
('candidate-check','background-color','#D5DFE9','',4),
('candidate-check','vertical-align','middle;','',4),
('candidate-content > div > span > strong','font-size','1.1rem;','',7),
('candidate-content-tr','min-height','500px;','',7),
('candidate-line','border-bottom','1px solid #DFE3E9;','',4),
('candidate-portrait','height','106px','',4),
('candidate-portrait','margin-bottom','5px;','',4),
('candidate-portrait','margin-left','0px;','',4),
('candidate-portrait','margin-top','5px;','',4),
('candidatetable','width','100%','',3),
('circle-checkbox[type=\"checkbox\"]','display','none','',11),
('circle-checkbox[type=\"checkbox\"]','opacity','0','',11),
('circle-checkbox[type=\"checkbox\"] + label div','background-color','var(--bs-body-bg)','',11),
('circle-checkbox[type=\"checkbox\"] + label div','border','1px solid var(--bs-light-text)','',11),
('circle-checkbox[type=\"checkbox\"] + label div','border-radius','50%','',11),
('circle-checkbox[type=\"checkbox\"] + label div','height','3.3rem','',11),
('circle-checkbox[type=\"checkbox\"] + label div','margin','auto','',11),
('circle-checkbox[type=\"checkbox\"] + label div','text-align','center','',11),
('circle-checkbox[type=\"checkbox\"] + label div','width','3.3rem','',11),
('circle-checkbox[type=\"checkbox\"] + label div i','color','var(--bs-body-bg)','',11),
('circle-checkbox[type=\"checkbox\"] + label div i','display','inline-block','',11),
('circle-checkbox[type=\"checkbox\"] + label div i','font-size','3.2rem','',11),
('circle-checkbox[type=\"checkbox\"]:checked + label div i','color','var(--bs-primary)','',11),
('circle-checkbox[type=\"checkbox\"]:checked:disabled + label div i','color','var(--bs-secondary-text)','',11),
('claim','color','rgb(255, 0 0);','',4),
('claim','font-family','\"Source Sans Pro\";','',4),
('claim','font-size','max(2.2vw, 32px);','',4),
('claim','font-weight','400;','',4),
('claim','line-height','max(3.0vw, 32px)','',4),
('claim','padding-bottom','20px;','',4),
('claim','padding-left','6px;','',4),
('claim','padding-right','6px;','',4),
('claim','padding-top','20px;','',4),
('claimparagraph','line-height','max(3.3vw, 26px);','',4),
('color-white','color','white','',4),
('column-bgkl','font-size','0.6rem;','',10),
('column-bgkl','text-align','right','',10),
('con-img > p > img','width','100%','',7),
('contact-bubble','background-color','rgb(4, 79, 146);','',8),
('contact-bubble','border','1px solid white;','',8),
('contact-bubble','border-radius','50%;','',8),
('contact-bubble','bottom','2vh;','',8),
('contact-bubble','box-shadow','2px 2px 4px #555;','',8),
('contact-bubble','font-size','max(3vw,24px);','',8),
('contact-bubble','height','max(5vw,48px);','',8),
('contact-bubble','line-height','max(5vw,48px);','',8),
('contact-bubble','position','fixed;','',8),
('contact-bubble','right','2vw;','',8),
('contact-bubble','text-align','center;','',8),
('contact-bubble','width','max(5vw,48px);','',8),
('contact-bubble-text','background-color','rgb(4, 79, 146);','',8),
('contact-bubble-text','border','1px solid white;','',8),
('contact-bubble-text','bottom','2vh;','',8),
('contact-bubble-text','box-shadow','2px 2px 4px #555;','',8),
('contact-bubble-text','color','white;','',8),
('contact-bubble-text','display','none','',8),
('contact-bubble-text','position','fixed;','',8),
('contact-bubble-text','right','2vw;','',8),
('contact-bubble-text:focus','display','inline;','',8),
('contact-bubble-text:hover','display','inline;','',8),
('contact-bubble:hover ~ div.contact-bubble-text','display','inline;','',8),
('debugborder','border','0px dashed cyan','',4),
('dimg > p > img','margin-top','24px;','',7),
('dimg > p > img','width','100%;','',7),
('footer','line-height','24px;','',7),
('footerinfoblock','width','175mm','',2),
('footerinfoblocklabel','text-align','right','',2),
('footerinfoblocklabel','width','20mm','',2),
('footerinfoblockvalue','text-align','right','',2),
('footerinfoblockvalue','width','20mm','',2),
('form-control','border-radius','0.125em','',0),
('gradient-dl','background','linear-gradient(90deg, rgba(107,137,169,1) 0%, rgba(35,81,127,1) 100%);','',4),
('gradient-ld',' background','linear-gradient(90deg, #CDD5DF 0%, #657D9B 100%);','',4),
('header-bar','box-sizing','border-box;','',7),
('header-bar','min-height','60px;','',7),
('header-bar','padding-left','5px','',7),
('header-bar','padding-right','5px','',7),
('header-topbar','  background','rgb(107,137,169);','',4),
('header-topbar',' background','linear-gradient(90deg, rgba(107,137,169,1) 0%, rgba(35,81,127,1) 100%);','',4),
('header-vh-4 > h3','font-size','max(1.2vw,18px);','',7),
('header-vh-4 > h3','line-height','max(1.3vw,20px);','',7),
('header-vh-4 > h3','margin-bottom','24px;','',7),
('headerbanner','color','white','',4),
('headerbanner','font-size','21pt','',4),
('headerbanner_green','color','rgb(175,204,122);','',4),
('hg-line','color','#DFE3E9;','',4),
('hint-circle-checkbox[type=\"checkbox\"]','display','none','',11),
('hint-circle-checkbox[type=\"checkbox\"]','opacity','0','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','border','1px solid var(--bs-light-text)','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','border-radius','50%','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','color','var(--bs-body-bg)','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','height','3.3rem','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','margin','auto','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','text-align','center','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1)','width','3.3rem','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1) i','color','var(--bs-body-bg)','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1) i','display','inline-block','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1) i','font-size','3.2rem','',11),
('hint-circle-checkbox[type=\"checkbox\"] + label div div:nth-child(1) i','margin-left','-2px','',11),
('hint-circle-checkbox[type=\"checkbox\"]:checked + label div div:nth-child(1) i','color','var(--bs-primary)','',11),
('hint-circle-checkbox[type=\"checkbox\"]:checked~.formbox','display','inline','',11),
('hint-circle-checkbox[type=\"checkbox\"]:not(:checked)~.formbox','display','none','',11),
('hr_notvoted','color','rgb(50,50,50)','',3),
('left-linklist li  a','color','#044f92;','',7),
('left-linklist li  a','padding-left','6px;','',7),
('left-linklist li  a:before','background-color','rgba(133,134,140,.15);','',7),
('left-linklist li  a:before','border-radius','4px;','',7),
('left-linklist li  a:before','content','\"\";','',7),
('left-linklist li  a:before','height','14px;','',7),
('left-linklist li  a:before','left','-2px;','',7),
('left-linklist li  a:before','margin-top','7px;','',7),
('left-linklist li  a:before','position','absolute;','',7),
('left-linklist li  a:before','width','14px;','',7),
('left-linklist li a:after','color','rgba(133,134,140,.5);','',7),
('left-linklist li a:after','content','\"\\f054\";','',7),
('left-linklist li a:after','font-family','\"Font Awesome 5 Pro\";','',7),
('left-linklist li a:after','font-size','8px;','',7),
('left-linklist li a:after','font-weight','900;','',7),
('left-linklist li a:after','left','3px;','',7),
('left-linklist li a:after','margin-top','2px;','',7),
('left-linklist li a:after','position','absolute;','',7),
('legitimation > ul','list-style-type','circle;','',7),
('lines-button','align-items','center;','',4),
('lines-button','display','block;','',4),
('lines-button','height','17px;','',4),
('lines-button','margin-right','0;','',4),
('lines-button','position','relative;','',4),
('lines-button','text-align','center;','',4),
('lines-button','width','37px;','',4),
('logout','background-color','rgb(175,204,122);','',4),
('menu-line','background-color','white;','',4),
('menu-line','content','\'\';','',4),
('menu-line','display','block;','',4),
('menu-line','height','3px;','',4),
('menu-line','left','0;','',4),
('menu-line','margin-bottom','5px;','',4),
('menu-line','margin-top','5px;','',4),
('menu-line','width','30px;','',4),
('menu-toggle','background-color','rgb(4,79,146);','',4),
('menu-toggle','height','77px','',4),
('menu-toggle','padding-bottom','30px;','',4),
('menu-toggle','padding-left','26px;','',4),
('menu-toggle','padding-right','20px;','',4),
('menu-toggle','padding-top','24px;','',4),
('menu-toggle','position','absolute;','',4),
('menu-toggle','right','-8px;','',4),
('menu-toggle','width','77px;','',4),
('menu-toggle:before','box-sizing','border-box;','',4),
('menu-toggle:before','height','100%;','',4),
('menu-toggle:before','left','0;','',4),
('menu-toggle:before','position','absolute;','',4),
('menu-toggle:before','top','0;','',4),
('menu-toggle:before','width','100%;','',4),
('mid-linklist li  a','color','#fff;','',7),
('midtext-size','font-size','18pt','',4),
('muc-page','color','#444;','',7),
('muc-page','font-family','\"Source Sans Pro\",Helvetica,Arial,Verdana,sans-serif;','',7),
('muc-page','font-size','max(1.2vw,18px);','',7),
('muc-page','line-height','max(1.6vw,26px);','',7),
('muc21-bp-fs > h1','font-size','1.4em;','',9),
('muc21-bp-fs > h1','font-weight','600;','',9),
('muc21-bp-fs > h1','line-height','1.4em;','',9),
('muc21-bp-fs > h1','margin-bottom','1.0em;','',9),
('muc21-bp-fs > h2','font-size','1.3em;','',9),
('muc21-bp-fs > h2','font-weight','600;','',9),
('muc21-bp-fs > h2','line-height','1.3em;','',9),
('muc21-bp-fs > h2','margin-bottom','1.0em;','',9),
('muc21-bp-fs > h3','color','rgb(0, 158, 212);','',9),
('muc21-bp-fs > h3','font-size','1.2em;','',9),
('muc21-bp-fs > h3','font-weight','600;','',9),
('muc21-bp-fs > h3','line-height','1.2em;','',9),
('muc21-bp-fs > h3','margin-bottom','0.1em;','',9),
('muc21-bp-fs > h4','font-size','1.1em;','',9),
('muc21-bp-fs > h4','font-weight','600;','',9),
('muc21-bp-fs > h4','line-height','1.1em;','',9),
('muc21-bp-fs-l','font-size','max(14pt, 1.4vw);','',9),
('muc21-bp-fs-l','line-height','max(16pt, 1.6vw);','',9),
('muc21-bp-fs-n','font-size','max(12pt, 0.8vw);','',9),
('muc21-bp-fs-n','line-height','max(14pt, 1.4vw);','',9),
('muc21-bp-fs-n-fixed','font-size','12pt;','',9),
('muc21-bp-fs-n-fixed','line-height','15pt;','',9),
('muc21-bp-fs-s','font-size','max(10pt, 0.5vw);','',9),
('muc21-bp-fs-s','line-height','max(12pt,1.2vw);','',9),
('muc21-bp-fs-xl','font-size','max(16pt, 3.3vw);','',9),
('muc21-bp-fs-xl','line-height','max(18pt, 3.8vw);','',9),
('muc21-pb-n','padding-bottom','40px;','',9),
('muc21-pb-xs','padding-bottom','8px;','',9),
('muc21-pr-xs','padding-right','8px;','',9),
('muc21-pt-n','padding-top','40px;','',9),
('muc21-pt-xs','padding-top','8px;','',9),
('nav-item','padding','2mm','',4),
('navbar','padding','0px','',4),
('nowrap','white-space',' nowrap','',4),
('number','text-align','right','',3),
('number','width','25mm','',3),
('page','font-family','DejaVu Sans','\'',1),
('page','font-size','8pt','\'',1),
('page','margin-bottom','25mm','\'',1),
('page','margin-left','0mm','\'',1),
('page','margin-right','0mm','\'',1),
('page','margin-top','27mm','\'',1),
('page','padding-left','25mm','',1),
('page','padding-right','20mm','',1),
('page','padding-top','10mm','',1),
('page-ballotpaper .candidate-portrait, .page-ballotpaper .picture','border-radius','5px','',10),
('page-ballotpaper .candidate-portrait, .page-ballotpaper .picture','width','64px','',10),
('page-ballotpaper > div > div:nth-child(2) > table > thead > tr > th.check','width','4rem;','',10),
('page-ballotpaper > div > div:nth-child(2) > table > thead > tr > th.check.align-middle > div','color','#fff','',0),
('page-ballotpaper p','margin-bottom','0px','',10),
('page-ballotpaper p.person1','font-weight','bold','',10),
('page-chooseballotpaper.container-fluid','margin-bottom','var(--container-fluid-margin-top)','',0),
('page-chooseballotpaper.row','margin-top','calc(2*var(--container-fluid-margin-top))','',0),
('page-claim',' background-color','var(--page-claim-bg)','',0),
('page-claim','color','var(--page-claim-color)','',0),
('page-claim','font-size','1.5rem','',0),
('page-claim','font-weight','700','',0),
('page-claim','min-height','1cm','',0),
('page-claim','padding-bottom','0.8rem','',0),
('page-claim','padding-top','0.8rem','',0),
('page-claim','text-align','center','',0),
('page-claim.container-fluid','margin-bottom','var(--container-fluid-margin-top)','',0),
('page-claim.container-fluid','margin-top','var(--container-fluid-margin-top)','',0),
('page-footer',' background-color','var(--page-footer-bg)','',0),
('page-footer','color','var(--page-footer-color)','',0),
('page-footer','margin-top','calc(3*var(--container-fluid-margin-top))','',0),
('page-footer','padding-top','var(--container-fluid-margin-top)','',0),
('page-footer .mid-linklist li a','color','#fff','',0),
('page-header','margin-top','calc(1*var(--container-fluid-margin-top))','',0),
('page-header > div > div > * > a > img','height','100%','',0),
('page-header > div > div > * > a > img','max-height','2cm','',0),
('page-header > div > div > * > a > img','object-fit','contain','',0),
('page-header > div > div > * > a > img','object-position','0px 50%','',0),
('page-header > div > div > * > a > img','width','100%','',0),
('page-header > div > div > div.col.text-end > a','--bs-btn-bg','#ccc','',10),
('page-header > div > div > div.text-end > div > div > a > i','color','var(--bs-primary)','',0),
('page-header > div > div > div.text-end > div > div > a > i > span','font-family','var(--bs-font-sans-serif)','',0),
('page-header > div > div > div:nth-child(2) > a > img','object-position','50% 50%','',0),
('page-legitimation-subtext','margin-top','calc(3*var(--container-fluid-margin-top))','',0),
('page-legitimation-subtext > div.container',' background-color','var(--bs-gray-200)','',0),
('page-legitimation-subtext > div.container','padding-top','var(--container-fluid-margin-top)','',0),
('page-login > div > div:nth-child(2)','margin-top','var(--container-fluid-margin-top)','',0),
('page-logout.container-fluid > div > * > div > a','height','100%','',10),
('page-logout.container-fluid > div > * > div > a','padding-top','0.2em;','',10),
('page-logout.container-fluid > div > * > div > a','width','100%','',10),
('pagebackground','bottom','0mm','\'',1),
('pagebackground','left','0mm','\'',1),
('pagebackground','position','fixed','\'',1),
('pagebackground','right','0mm','\'',1),
('pagebackground','top','0mm','\'',1),
('pagebackground','z-index','-1','\'',1),
('pagecontent','border','1px dashed black','\'',1),
('pagecontent','margin-left','25mm','\'',1),
('pagecontent','margin-right','10mm','\'',1),
('pagecontent','margin-top','70.46mm','\'',1),
('pagecontent','position','relative','',1),
('pageinfo','position','fixed','\'',1),
('pageinfo','right','10mm','\'',1),
('pageinfo','text-align','right','',1),
('pageinfo','top','-5mm','\'',1),
('pageinfo','width','75mm','\'',1),
('pagemargins','padding-bottom','5mm','\'',1),
('pagemargins','padding-left','25mm','\'',1),
('pagemargins','padding-right','15mm','\'',1),
('pagemargins','padding-top','10mm','\'',1),
('rank','color','rgb(90,90,90)','',3),
('rank','padding-right','5mm','',3),
('rank','text-align','right','',3),
('rank','width','15mm','',3),
('sec > p:nth-child(4) > em','font-style','normal','',7),
('sec > p:nth-child(4) > em','text-decoration','underline','',7),
('senderaddress','font-size','5pt','\'',1),
('senderaddress','left','25mm','',1),
('senderaddress','position','fixed','',1),
('senderaddress','top','10mm','\'',1),
('step','color','rgb(255, 255, 255);','',4),
('step','font-family','\"Source Sans Pro\";','',4),
('step','font-size','max(3vw, 32px);','',4),
('step','font-weight','600','',4),
('step','line-height','max(3.3vw, 36px);','',4),
('step','padding-left','6px;','',4),
('step','padding-right','6px;','',4),
('stepparagraph','line-height','max(1.3vw, 36px);','',4),
('sz-header-text','font-size','18pt','',4),
('szg-header-sitze','background-color','#2D75B1','',4),
('szg-header-sitze','color','white','',4),
('szg-header-sitze','font-size','44pt','',4),
('szg-header-sitze','font-weight','bold','',4),
('szg-header-sitze','text-align','center','',4),
('szg-header-sitze','vertical-align','middle','',4),
('szg-header-text','background-color','#003365','',4),
('szg-header-text','color','white','',4),
('szg-header-text','font-size','16pt','',4),
('szg-header-text','line-height','44pt','',4),
('szg-header-text','padding-top','12px','',4),
('szg-header-text','text-align','center','',4),
('szg-header-text','vertical-align','middle','',4),
('td_notvoted','border-bottom','0.1mm solid black','',3),
('td_notvoted','font-size','0.5em','',3),
('text-contour','text-shadow','-0.02rem -0.02rem 0 #666, 0.01rem -0.02rem 0 #666, -0.01rem 0.01rem 0 #666, 0.01rem 0.01rem 0 #666','',0)//