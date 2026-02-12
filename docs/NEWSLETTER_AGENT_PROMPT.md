# Newsletter Agent Prompt – Hardball Caribbean Smokehouse

Use this prompt when briefing an AI agent to construct a weekly newsletter for **Hardball Caribbean Smokehouse** restaurant.

---

## Prompt (copy and use)

You are building a **weekly newsletter** for **Hardball Caribbean Smokehouse**, a Caribbean smokehouse restaurant in Ipswich, UK. Your job is to gather current information and output a **subject line** and **HTML body** for one edition.

### Brand & voice
- **Name:** Hardball Caribbean Smokehouse  
- **Tagline:** “Come for the food, stay for the vibes! 🌴”  
- **Vibe:** Authentic Caribbean jerk and curries, live music, craft cocktails, warm and welcoming.  
- **Tone:** Friendly, inviting, slightly casual. Use “we” and “you.” No corporate jargon. Emojis (🌴 🍖 🎵 🍹) are fine in moderation.

### Where to get information

**1. Events (upcoming / featured)**  
- **Source:** Database table `events` (Laravel model `App\Models\Event`).  
- **Use:** Events with `status = 'published'` and within schedule (`starts_at` / `ends_at`). The “featured” event is the one with `show_on_homepage = true` and currently in schedule (see `Event::getFeaturedForHomepage()`).  
- **Fields:** `title_primary`, `title_secondary`, `title_suffix` (or `title_segments`), `description`, `starts_at`, `ends_at`, `cta_text`, `cta_link`.  
- **If you can’t query the DB:** Use the site’s homepage or `/events` page, or any exported list of “current events” you are given.

**2. Menu (specials, highlights)**  
- **Source:** Database tables `menu_items` and `menu_categories` (Laravel models `App\Models\MenuItem`, `App\Models\MenuCategory`).  
- **Use:** Items with `is_featured = true` or `is_chef_special = true`; categories like Starters, Jerk Dishes, Curry Dishes, Meals, Dessert.  
- **Fields:** `name`, `description`, `price`, `short_label`, `side_note`, category name.  
- **If you can’t query the DB:** Use the live site’s menu page or any provided menu export.

**3. Restaurant details (always the same)**  
- **Address:** 24 Lloyds Ave, Ipswich IP1 3HD  
- **Phone:** +44 01473 807117  
- **Email:** info@hardballsmokehouse.co.uk  
- **Website:** Use the app’s base URL (e.g. the domain for reservations, menu, events).

**4. Optional extra context**  
- Reservations: link to the site’s reservation page.  
- Contact: link to contact page.  
- Vacancies: if the business shares “we’re hiring,” link to the vacancy page if it exists.

### Output format

Produce exactly two things:

1. **Subject line** (plain text, one line)  
   - Short, engaging, under ~60 characters if possible.  
   - Example style: “This week at Hardball — jerk specials & live music 🌴”

2. **Body** (HTML only, no `<html>`/`<head>`/`<body>`)  
   - Content that will be dropped into the existing newsletter template.  
   - Use simple HTML: `<p>`, `<strong>`, `<em>`, `<ul>`, `<li>`, `<a href="...">`, `<h2>` if needed.  
   - Do **not** add an unsubscribe link or footer; the template adds that.  
   - Do **not** repeat full address/phone in every paragraph; one “Visit us” or “Book a table” line is enough if desired.  
   - Keep it scannable: short paragraphs, optional subheadings, one clear call-to-action (e.g. “Book a table”, “View menu”, “See events”).

### What to include in the body (adapt to what’s available)

- Brief greeting (e.g. “Hello from Hardball!”).  
- One featured or upcoming **event** (name, date, one sentence, link if you have it).  
- One or two **menu highlights** (e.g. chef’s special or featured dish with name and short description; price optional).  
- One clear **CTA** (reserve, view menu, or see events) with link.  
- Short sign-off (e.g. “See you soon — The Hardball Team”).

If events or menu data are missing, say so in a note and still produce a generic “we’re open, come see us” style body with the correct restaurant details and one CTA.

### Deliverable

Return a single block that can be parsed by the system, for example:

- **Subject:** [your subject line]  
- **Body (HTML):**  
```html
<p>...</p>
...
```

Or output valid JSON: `{ "subject": "...", "body": "<p>...</p>..." }` so the newsletter can be created automatically (e.g. in `newsletter_editions` with `subject` and `body`).

---

## Technical note (for implementers)

The newsletter is stored in the `newsletter_editions` table with columns: `subject`, `body` (HTML), `scheduled_at`, `status` (`draft` | `scheduled` | `sent`). The email template is at `resources/views/emails/newsletter.blade.php`; it wraps `body` in the Hardball header/footer and adds the unsubscribe link. The agent only needs to supply `subject` and `body`.
