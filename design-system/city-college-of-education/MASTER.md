# UI/UX Pro Max Design System — City College of Education, Mararaba

## 1. Product & Visual Direction
- **Product Category**: Tertiary Educational Institution / University & College Portal (`Research Lab / University Department` + `Academic / Scholarly`)
- **Primary Style**: **Swiss Modernism 2.0 + Editorial Grid** (Clean academic hierarchy, high-trust institutional framing, bento stat blocks, glassmorphic badges, restrained motion)
- **Accessibility Target**: WCAG 2.1 AA / AAA (4.5:1+ body contrast, 44×44px minimum touch targets, visible `:focus-visible` rings, `aria-hidden="true"` on all decorative SVGs, `prefers-reduced-motion` support)

## 2. Official Color Tokens (Derived from CCoE Crest Logo)
| Token | Value | Usage |
|-------|-------|-------|
| `--ccoe-navy` | `#00337C` | Primary institutional brand color, headers, primary buttons |
| `--ccoe-navy-dark` | `#001F4D` | Deep hero/footer surface, high-contrast headings |
| `--ccoe-gold` | `#F5B800` | Secondary brand accent, primary CTAs, focus rings, active indicators |
| `--ccoe-gold-hover` | `#D9A300` | Interactive hover state for gold CTAs |
| `--ccoe-green` | `#007A33` | Accreditation & entrepreneurship badges, success states |
| `--ccoe-maroon` | `#8B0029` | Crest ribbon heritage accent |
| `--ccoe-surface` | `#FFFFFF` | Primary card & content surface |
| `--ccoe-bg-subtle` | `#F8FAFC` | Alternating section background |
| `--ccoe-text-primary` | `#0F172A` | Primary body copy (15.8:1 contrast on white) |
| `--ccoe-text-secondary` | `#475569` | Metadata & supporting captions (7.5:1 contrast on white) |
| `--ccoe-border` | `#E2E8F0` | Hairline Swiss grid & card dividers |

## 3. Typography System (`Academic/Research` Pairing)
- **Heading Font (`--font-serif`)**: `'Crimson Pro', 'Merriweather', Georgia, serif` (`font-weight: 600–700`, `text-wrap: balance`, `letter-spacing: -0.015em`)
- **Body & UI Font (`--font-sans`)**: `'Atkinson Hyperlegible', 'Inter', system-ui, sans-serif` (`16px` base, `line-height: 1.65`, `max-width: 68ch` measure)

## 4. Icon & Interaction Discipline (`pro-rules.md`)
- **Zero Emojis as UI Icons**: All structural, navigation, metadata, and card icons use crisp 2px-stroke inline Lucide SVGs (`width="20" height="20"`, `aria-hidden="true"`).
- **Touch Targets**: All interactive links, buttons, filter pills, and form controls enforce `min-height: 44px`.
- **Motion**: Context-aware `cubic-bezier(0.22, 1, 0.36, 1)` transitions (`180ms–240ms`), disabled automatically under `@media (prefers-reduced-motion: reduce)`.
