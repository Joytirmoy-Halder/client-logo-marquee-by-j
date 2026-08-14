# Client Logo Marquee by J

A client logo strip for Elementor that scrolls forever without a seam, without
jQuery, and without costing you frames.

The loop is a single composited CSS transform. There is no per-frame
JavaScript, no scroll listener and no carousel library. The script runs a
handful of times in the life of the page: once to work out how many copies of
your logo set are needed to cover the screen, and again if the container
resizes. While the strip is off-screen the animation is paused outright, so
scrolling past it costs nothing.

---

## Install

1. Upload the `client-logo-marquee-by-j` folder to `/wp-content/plugins/`, or
   upload the zip through **Plugins > Add New > Upload Plugin**.
2. Activate it.
3. In Elementor, find **Client Logo Marquee** under the **By J** category.

Requires Elementor 3.5+, WordPress 5.9+, PHP 7.4+. Elementor Pro is not needed.

---

## Image resolution guide

| Logo height set in the panel | Export each logo at | Typical file |
| --- | --- | --- |
| 34 px (mobile default) | 68 px tall | 240 x 68 |
| 44 px (desktop default) | 88 px tall | 320 x 88 |
| 60 px | 120 px tall | 430 x 120 |

- **Height is what matters.** The widget sizes every logo by height and lets
  the width fall where it may, so a wide wordmark and a square badge both look
  right. Export at 2x your chosen height for retina screens.
- **SVG is best** for wordmarks: sharp at any size, usually 2-6 KB. Otherwise
  PNG-24 with transparency. Avoid JPG, it cannot do transparency.
- **Trim the empty space** around each logo before uploading. Padding baked
  into the file is the single biggest reason a logo strip looks uneven.
- **Keep them under 40 KB each.** With lazy loading and srcset the strip costs
  almost nothing, but only if the source files are sane.
- Width is capped by **Logo Max Width** (200px by default) so one very wide
  logo cannot dominate the row.

---

## Controls

### Content > Client Logos

| Control | What it does |
| --- | --- |
| Logos (repeater) | Logo image, client name, optional link |
| Client Name | Used as image `alt` text. Never displayed |
| Link | Optional, with new tab and nofollow options |
| Image Resolution | Which size to serve. Choose Custom for an exact size, and set only the Height |
| Accessible Label | What screen readers announce for the strip |

### Content > Marquee

| Control | Default | Notes |
| --- | --- | --- |
| Direction | Right to left | Or left to right |
| Loop Duration | 38s / 30s / 22s | Per breakpoint. Higher is slower |
| Space Between Logos | 72 / 56 / 40 px | Per breakpoint, in px, em or vw |
| Pause On Hover | On | Also pauses on keyboard focus |
| Pause When Off-Screen | On | Leave this on |
| Fade Edges | On | Logos dissolve at both ends |
| Fade Width | 140 / 48 px | Per breakpoint |

### Style > Logos

Presentation (bare logos or cards), logo height and max width per breakpoint,
resting greyscale and opacity, hover greyscale, opacity, scale and transition
speed.

### Style > Cards

Only shown in card mode: minimum width, padding, radius, background, border,
shadow, plus a separate hover tab with background, border colour, shadow, lift
distance and an optional sheen sweep.

---

## How the seamless loop works

The track holds N identical copies of your logo set and slides left by exactly
one copy width, then snaps back. Because copy 2 is sitting precisely where
copy 1 started, the reset is invisible.

Two details make it exact:

- The spacing is a right margin on every item **including the last one**, so a
  set measures exactly one repeat of the pattern. A flex `gap` would leave the
  seam one gap short and the loop would visibly stutter.
- The number of copies is written to CSS as `--clmj-sets` and the keyframe
  shifts by `calc(-100% / var(--clmj-sets))`, so the maths stays correct no
  matter how many logos you add or how wide the screen is.

---

## Performance

- **CSS animation, not JavaScript.** The transform runs on the compositor.
- **Assets load on demand.** The 5 KB of CSS and 6 KB of JS are registered
  through `get_style_depends()` / `get_script_depends()`, so pages without the
  widget never download them.
- **No jQuery.**
- **Off-screen means paused.** An IntersectionObserver stops the animation
  when the strip leaves the viewport, and `will-change` is dropped at the same
  time so the layer can be released.
- **No layout shift.** Logos render through the core attachment helper, so they
  arrive with intrinsic width and height, `srcset` and `loading="lazy"`.
- **No polling.** A ResizeObserver handles both container resizes and logos
  finishing their download.

---

## Accessibility

- The strip is a labelled `region`.
- Duplicated logos are `aria-hidden` and removed from the tab order, so a
  screen reader hears your client list once, not four times.
- Hovering **or focusing** pauses the animation, so keyboard users can reach
  every link.
- With `prefers-reduced-motion: reduce` the marquee is not merely stopped, it
  becomes a static centred grid of every logo, and the edge fades are removed.

---

## Troubleshooting

**The strip has a gap in it.** You have very few logos and JavaScript is not
running. Check the browser console. The PHP output ships two copies, and the
script adds more as needed to cover wide screens.

**Logos look different sizes.** They almost certainly have different amounts of
baked-in padding. Trim the source files.

**It does not move in the editor.** Elementor's editor iframe can suppress
animation while a widget is selected. Preview the page.

**Nothing scrolls, everything is stacked.** Your OS has reduced motion enabled.
That is the intended fallback.

---

## Changelog

### 1.0.3

- **Logo sizing is now enforced.** Plenty of themes and page builders set
  `img { width: 100% }` inside widget containers. That rule out-specified the
  widget's own sizing and stretched each logo to the full width of the strip,
  blurring it in the process. The sizing rule now carries higher specificity,
  and the values still come from the panel controls.
- **Assets load inside the Elementor editor preview.** A newly dropped widget
  is styled immediately instead of after an editor reload.
- **Default Image Resolution is now Full**, so a logo is never handed to a
  cropped WordPress size by accident.

### 1.0.2

- **Custom** is now available in the Image Resolution dropdown. It was hidden
  in 1.0.x because a custom size crops every logo to one fixed box, which is
  wrong for logos of differing shapes. It is resolved through Elementor's own
  image size handler now, so it generates and caches a real resized file. Set
  the **Height** only and leave the **Width** empty to keep each logo's ratio.
  Note that a custom size serves one fixed file rather than a responsive
  `srcset`.

### 1.0.1

- Fixed a fatal error on some Elementor versions. The widget overrode
  `has_widget_inner_wrapper()`, whose signature differs between Elementor
  releases, and a mismatch is a compile-time fatal that takes down every page
  that loads the widget class. The override was a one-div micro-optimisation,
  so it has simply been removed.

### 1.0.0

- First release.

---

GPL-2.0-or-later.
