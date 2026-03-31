# Views & Field Placement Standards

Guidelines for deciding **which fields go where** across MintHCM views.

For technical syntax of recordviewdefs, eslistviewdefs, subpaneldefs — see the skill-specific references. 

## Record View (recordviewdefs.php)

MintHCM record view serves as both detail and edit view. Structure it with **3 sections** inside `MintPanelRecordDetails`:

### Section 1: Overview / Basic Info

The main section — essential, frequently used fields. Keep it focused, don't overwhelm the user.

**Include:**
- `name` field (or `first_name` + `last_name` for person modules)
- Key dates (hire_date, start_date, expiration_date)
- Status / key dropdowns
- Primary contact method (email, phone) for person/company modules
- Required fields
- Key relate fields (most important relationships: e.g., employee, account, contact)
- Main description field

**Field grouping rules** — place fields close together when they:
- **Depend on each other** — one controls visibility/required/value of another (e.g., status + rejection_reason)
- **Extend each other's meaning** — status + status_description, start_date + end_date
- **Are semantically related** — office_phone + mobile_phone, city + postal_code
- **Complement each other** — meeting_date + meeting_location

**Layout tips:**
- Put fields of similar height in the same row (address fields together, multiselects together)
- Description/text fields should span full width (`['description']` — single-element array)
- Don't display `currency_id` as a separate field — it shows within the currency field's label automatically

### Section 2: More Information

Secondary, less frequently used details. This section can be collapsed by default.

**Include:**
- Detailed supplementary information
- Additional description fields
- Rarely needed but useful data
- Non-essential relate fields

### Section 3: Other

Every module has an "Other" section with system/audit fields (assigned_user, date_entered, date_modified, created_by, modified_by). **The system adds this section automatically** — you do not need to define it in recordviewdefs.

### Fields NOT on Record View

- Technical/hidden fields invisible to end users
- `currency_id` (displayed within currency field label)
- Individual address components — use a `fieldset` to group them instead

## List View (eslistviewdefs.php)

### Default columns

Optimal count: **6–7 default columns**.

**Should be default (`'default' => true`):**
- `name` — always
- Primary contact fields (email, phone) for person/company modules
- `assigned_user_name`
- Key dates (date_modified, expiration_date, event start)
- Key dropdowns (status, qualification, priority)
- Flex relate / parent field

**Should be available but NOT default:**
- Remaining relate fields
- Secondary dates
- Secondary dropdowns
- Other informational fields

**Should NOT appear in list view at all:**
- Technical/hidden fields
- `text` / textarea fields (exception: street address)
- `iframe` fields
- `image` fields
- Unusual/exotic field types

### Performance note

Minimize relate fields in default columns — each one generates a JOIN. Include only the most important relationships.

### All columns should be sortable

Set `'sortable' => false` only when technically impossible (e.g., computed non-db fields, email).

## Subpanels (subpaneldefs.php)

### General rules

- **Design each subpanel independently** — the right column set depends on the relationship context
- **Don't show the parent relationship** — e.g., no "Account" column in a subpanel under an Account record
- **Never leave default subpanels** showing only name + date_entered — always configure meaningful columns

### Column selection

Follow the same principles as list view but adapted to the relationship context:
- 4–6 columns (subpanels have less horizontal space)
- Include fields that help distinguish records within this specific relationship
- Skip the parent field (it's implicit from the context)
