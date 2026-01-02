# Google Search Console

## 1. Add your property
1. Visit [https://search.google.com/search-console](https://search.google.com/search-console) and add the site as both **Domain** and **URL prefix** (one entry for `https://` and one for `http://` if needed).
2. Choose the verification method that fits your hosting. A screenshot placeholder:
   ![Add property view](docs/images/gsc-add-property.png)

## 2. Verification methods
- **DNS TXT (recommended for domain properties)**: Add the TXT record provided by Google to your DNS zone (or copy the hash to your IT team). Wait for propagation before clicking **Verify**.
- **HTML file**: Download the verification file and place it under `public/googleXXXXXXXXXXXX.html`, then request verification.
- **Meta tag**: Copy the `content` attribute from the meta snippet and paste it into the new **Google verification code** field on the admin settings page (`site.seo.google_verification`) or set `GOOGLE_SITE_VERIFICATION` in `.env`. Google will see the tag (`<meta name="google-site-verification" content="XYZ">`) automatically rendered in every page.
  ![Meta tag placeholder](docs/images/gsc-meta.png)

## 3. Submit the sitemap
1. In Search Console, browse to **Sitemaps**.
2. Submit `/sitemap-index.xml`.
3. Wait for the sitemap to be processed and ensure there are no errors.

## 4. Request indexing
1. Use the **URL Inspection** tool to test a representative page.
2. Click **Request indexing** when the page is valid.
3. Repeat for priority landing pages (home, announcements, news, citizen charter services).

## 5. Monitoring
- Check for crawl errors and coverage warnings weekly.
- Use the **Performance** report to monitor clicks and impressions for `/news`, `/announcements`, and `/downloads`.
