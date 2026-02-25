# Government Website Compliance Checklist

Use this checklist to ensure the site meets minimum expectations for a public-sector web presence.

- **Accessibility (WCAG)** – Run automated audits (Lighthouse, axe) and confirm manual spot checks for color contrast, focus states, skip links, and semantic markup to comply with WCAG 2.1 AA standards.
- **Privacy policy & terms of use** – Publish an up-to-date privacy policy and terms-of-use statement, and link them from the footer and during any data capture flows (appointments, contact forms, documents).
- **Cookie notice** – Display a clear consent banner when tracking cookies are enabled; respect opt-outs and document which cookies are essential vs. analytical.
- **Contact details & official address** – Publish the official organization names, physical address, phones, emails, and map links so citizens know how to reach the department.
- **Security headers** – Enforce HSTS, a baseline Content Security Policy, `X-Frame-Options`, `X-Content-Type-Options`, and `Referrer-Policy` on every response (the middleware already injects some headers, verify in staging).
- **HTTPS everywhere** – Redirect all HTTP traffic to HTTPS and ensure the TLS certificate is valid, renewed, and trusted by browsers.
- **Backups & audit logs** – Keep regular backups of the database and file storage, and log administrative actions (settings updates, document uploads, scheduling).
- **Content ownership & publishing cadence** – Document who owns each section of the site and how often content is reviewed/updated to prevent stale information.
- **Language support (am/en)** – Keep both Amharic and English versions of navigation, hero content, and legal notices synchronized.
- **Disaster recovery basics** – Maintain a recovery plan with responsibilities, recovery time objectives (RTO), and contact information for emergency communications.
