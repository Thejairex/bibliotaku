# Design System Specification: The Cinematic Archive

## 1. Overview & Creative North Star
### Creative North Star: "The Digital Curator"
This design system moves away from the cluttered, data-heavy grids of traditional media trackers. Instead, it adopts the persona of a **high-end digital gallery**. The goal is to treat every anime title or manga volume as a piece of art. 

We break the "template" look by utilizing **intentional asymmetry** and **tonal depth**. Rather than rigid tables, we use expansive white space (breathing room) and a "High-Editorial" type scale. The aesthetic is "Soft Minimalism"—where the interface recedes to let the vibrant cover art of the media become the hero.

---

## 2. Colors & Surface Philosophy
The palette is rooted in deep obsidian tones, punctuated by electric violets that signify action and selection.

### The "No-Line" Rule
**Borders are a failure of hierarchy.** Designers are prohibited from using 1px solid borders to define sections. Content blocks must be separated by:
1.  **Tonal Shifts:** Placing a `surface-container-low` card against a `surface` background.
2.  **Negative Space:** Using the `8` (2rem) or `10` (2.5rem) spacing tokens to create mental groupings.

### Surface Hierarchy & Nesting
Treat the UI as a series of layered obsidian sheets. 
*   **Base:** `surface` (#0e0e0e) for the primary background.
*   **Secondary Layers:** `surface-container` (#1a1919) for main content areas.
*   **Floating Elements:** `surface-container-highest` (#262626) for active cards or modals.
*   **The Glass Rule:** For floating navigation or headers, use `surface-container` at 70% opacity with a `24px` backdrop-blur. This ensures the media colors "bleed" through the UI, making it feel alive.

### Signature Textures
Main CTAs (like "Start Watching") should not be flat. Use a linear gradient from `primary` (#ba9eff) to `primary-dim` (#8455ef) at a 135-degree angle to provide a "lithic" glow that mimics high-end hardware.

---

## 3. Typography
We use a dual-font system to balance character with readability.

*   **Display & Headlines (Manrope):** Chosen for its geometric purity and modern "tech" feel. Use `display-lg` for series titles to create an editorial, magazine-like hero section.
*   **Body & Labels (Inter):** The workhorse. Inter provides maximum legibility for synopsis text and metadata at smaller scales (`body-sm`).

**Editorial Intent:** Use `headline-lg` for section headers (e.g., "Trending This Season") but pair it with a `label-md` in `primary` color for the sub-headline to create a sophisticated, tiered reading experience.

---

## 4. Elevation & Depth
### The Layering Principle
Hierarchy is achieved through **Tonal Stacking**. To make a "Watch List" card stand out, do not add a border. Place a `surface-container-highest` card on top of a `surface-container-low` sidebar. This 12% shift in brightness is enough for the human eye to perceive depth without visual noise.

### Ambient Shadows
Shadows must be invisible until noticed.
*   **Token:** `0px 20px 40px rgba(0, 0, 0, 0.4)`
*   Shadows should only be applied to elements on the highest elevation (Modals, Popovers). For lower-level cards, rely purely on tonal shifts.

### The "Ghost Border" Fallback
If an image (e.g., a very dark manga cover) bleeds into the background, use a **Ghost Border**: 1px solid `outline-variant` (#484847) at **15% opacity**. It should be felt, not seen.

---

## 5. Components

### Buttons
*   **Primary:** Rounded `full` (pill-shaped). Gradient fill (`primary` to `primary-dim`). Text: `label-md` bold, color `on-primary`.
*   **Tertiary:** No background. `surface-variant` text. High-state hover: background shifts to `surface-container-high`.

### Media Cards (The Signature Component)
*   **Radius:** `xl` (3rem) for the outer container; `lg` (2rem) for the internal image.
*   **Layout:** Forbid dividers. Use a `1.5rem` (`6`) gap between the title and the metadata. 
*   **Hover State:** Subtle scale-up (1.02x) and a shift from `surface-container` to `surface-container-highest`.

### Chips (Genre Tags)
*   **Style:** `surface-container-high` background. No border.
*   **Typography:** `label-sm` in `on-surface-variant`.
*   **Shape:** `full` (pill).

### Input Fields
*   **Surface:** `surface-container-low`.
*   **State:** On focus, the "Ghost Border" opacity increases to 40% and the label shifts to `primary`.

---

## 6. Do’s and Don’ts

### Do:
*   **Do** use `xl` (3rem) corners for large layout containers to create a friendly, organic feel.
*   **Do** use `secondary` (#9093ff) for "Completed" or "Success" states to differentiate from the `primary` purple.
*   **Do** embrace asymmetry. Offset your hero text to the left while the media art bleeds off the right edge of the screen.

### Don’t:
*   **Don’t** use pure white (#FFFFFF) for long-form body text; use `on-surface-variant` (#adaaaa) to reduce eye strain in dark mode.
*   **Don’t** use divider lines. If you feel the need for a line, increase the `spacing` token instead.
*   **Don’t** use `none` or `sm` roundedness. This system is defined by its "xl" softness; sharp corners break the brand soul.