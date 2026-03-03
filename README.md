# Brand My Login

A lightweight WordPress plugin by **Techoven Solutions** that replaces the default WordPress login branding with your own logo and colors. Designed for business owners who want a professional look without bloated settings.

## Features

- Upload a custom logo for the `/wp-login.php` page
- Pick a single brand color for buttons and links
- Clean settings screen under **Settings » Brand My Login**
- Works on desktop, tablet, and mobile without slowing the site
- Deactivating or deleting the plugin restores the default WordPress login page automatically

## Installation

1. Download or clone this repository into `wp-content/plugins/brand-my-login`.
2. From your WordPress admin dashboard go to **Plugins** and activate **Brand My Login**.
3. Navigate to **Settings » Brand My Login** to upload your logo and choose a brand color.

## Usage

1. Upload a logo (optional). If you skip this step, the default WordPress logo is used.
2. Select your brand color using the color picker.
3. Click **Save Changes**.
4. Visit `/wp-login.php` (or `/wp-admin`) and confirm the new look:
   - Logo area now displays your uploaded image (perfectly scaled)
   - "Log In" button uses your color
   - "Lost your password?" and "Back to site" links inherit the same color

## Success Checklist

- ✅ Logo updates instantly after saving
- ✅ Button/link color changes immediately and can be toggled between any colors
- ✅ Settings screen is simple enough for non-technical users
- ✅ Removing the plugin reverts the login page without leftover code

## Development Notes

- Options stored as a single array under `brand_my_login_options`
- Admin assets use the core media uploader and color picker
- Front-end tweaks are injected only on the login screen for zero performance impact elsewhere

## Contributors

- Techoven Solutions (@msufiyanpatel)

Plugin crafted with ❤️ by **Techoven Solutions** for a branded WordPress experience.
