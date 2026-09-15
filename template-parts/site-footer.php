<?php
/**
 * Site footer — 4-column grid (Brand+contact / Our Services / Quick Links / Legal) + copyright bar.
 * Pixel-identical across all 55 sampled Figma pages. Always injected, not page content.
 */
$logo = get_template_directory_uri() . '/assets/icons/logo.png'; // TODO: swap in the real logo asset
$year = wp_date( 'Y' );

/**
 * Social icon set — same convention as site-header.php's skyline_nav_icon(): original stroke-only
 * line-icon shapes (not copied from any icon library), 24x24 viewBox. Real destination URLs
 * pulled directly from the live skylinecruises.com homepage/footer (2026-09-15), not invented or
 * routed through the old live site's bit.ly tracking links used on /followus/.
 */
function skyline_social_icon( $name ) {
	// Each glyph stands alone -- no outer circle/square of its own -- since .site-footer__social a
	// is already the circular badge container; wrapping a shape in its own container shape too
	// just fights the button and reads as clutter. Facebook is a real filled "f" ribbon (a stroke-
	// only centerline reads as an abstract squiggle, not a letterform); Instagram keeps its rounded
	// square + ring + dot since that shape *is* the recognizable mark, just sized to sit inside the
	// button with breathing room; Twitter/X and YouTube are bold solid glyphs, no extra frame.
	$paths = [
		'facebook'  => '<path d="M15.5 21v-8h2.4l.4-3.2h-2.8V7.8c0-.9.3-1.5 1.7-1.5h1.4V3.4c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H9.4v3.2h2.7v8z" fill="currentColor" stroke="none"/>',
		'instagram' => '<rect x="4.5" y="4.5" width="15" height="15" rx="4.5" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3.4" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="16.3" cy="7.7" r="0.9" fill="currentColor" stroke="none"/>',
		'twitter'   => '<path d="M5.5 5.5l13 13M18.5 5.5l-13 13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
		'youtube'   => '<rect x="3.5" y="7" width="17" height="10" rx="3" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M10.3 10.2l4.6 1.8-4.6 1.8z" fill="currentColor" stroke="none"/>',
	];
	$inner = $paths[ $name ] ?? '';
	return '<svg viewBox="0 0 24 24" aria-hidden="true">' . $inner . '</svg>';
}

$social_links = [
	[ 'label' => 'Facebook', 'href' => 'https://www.facebook.com/SkylineCruises/', 'icon' => 'facebook' ],
	[ 'label' => 'Instagram', 'href' => 'https://www.instagram.com/skylinecruises/', 'icon' => 'instagram' ],
	[ 'label' => 'Twitter', 'href' => 'https://twitter.com/SkylinePrincess/', 'icon' => 'twitter' ],
	[ 'label' => 'YouTube', 'href' => 'https://www.youtube.com/channel/UCbJ3-e6Vn_A0E9Wzpsht7ow', 'icon' => 'youtube' ],
];
?>
<footer class="site-footer">
	<div class="site-footer__inner">
	<div class="site-footer__columns">
		<div class="site-footer__brand">
			<?php if ( is_active_sidebar( 'footer-brand' ) ) : ?>
				<?php dynamic_sidebar( 'footer-brand' ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( $logo ); ?>" alt="Skyline Cruises" width="114" height="51" />
				<p>New York's <strong>premier luxury</strong> yacht experience since 1993.</p>
				<div class="site-footer__contact">
					<div class="site-footer__contact-row">
						<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<span>(718) 446-1100</span>
					</div>
					<div class="site-footer__contact-row">
						<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
						<span>info@skylinecruises.com</span>
					</div>
					<div class="site-footer__contact-row">
						<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						<span><strong>World's Fair Marina</strong><br />Flushing, NY 11368</span>
					</div>
				</div>
				<div class="site-footer__social">
					<?php foreach ( $social_links as $social ) : ?>
						<a href="<?php echo esc_url( $social['href'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $social['label'] ); ?>"><?php echo skyline_social_icon( $social['icon'] ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-footer__services">
			<?php if ( is_active_sidebar( 'footer-services' ) ) : ?>
				<?php dynamic_sidebar( 'footer-services' ); ?>
			<?php else : ?>
				<h4>Our Services</h4>
				<ul>
					<li><a href="/nyc-dinner-cruises/">Dinner Cruises</a></li>
					<li><a href="/yacht-charter/">Private Charters</a></li>
					<li><a href="/weddings/">Wedding Packages</a></li>
					<li><a href="/corporate-cruises/">Corporate Events</a></li>
					<li><a href="/nyc-party-cruises/">Special Occasions</a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div class="site-footer__quicklinks">
			<?php if ( is_active_sidebar( 'footer-quicklinks' ) ) : ?>
				<?php dynamic_sidebar( 'footer-quicklinks' ); ?>
			<?php else : ?>
				<h4>Quick Links</h4>
				<ul>
					<li><a href="/about/">About Us</a></li>
					<li><a href="/the-ship/">Our Fleet</a></li>
					<li><a href="/about/clients-testimonials/">Testimonials</a></li>
					<li><a href="/picture-gallery-of-skyline-cruises/">Gallery</a></li>
					<li><a href="/notes-from-the-deck/">Blog</a></li>
					<li><a href="/about/faq/">FAQs</a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div class="site-footer__legal">
			<?php if ( is_active_sidebar( 'footer-legal' ) ) : ?>
				<?php dynamic_sidebar( 'footer-legal' ); ?>
			<?php else : ?>
				<h4>Legal</h4>
				<ul>
					<li><a href="/privacy-policy/">Privacy Policy</a></li>
					<li><a href="/cookie-policy/">Cookie Policy</a></li>
					<li><a href="/terms-of-service/">Terms of Service</a></li>
					<li><a href="/cancellation-policy/">Cancellation Policy</a></li>
					<li><a href="/accessibility/">Accessibility</a></li>
				</ul>
			<?php endif; ?>
		</div>
	</div>

	<div class="site-footer__copyright">
		&copy; <?php echo esc_html( $year ); ?> Skyline Cruises. All rights reserved. | Proudly serving New York since 1993 | <a href="/privacy-policy/">Privacy Policy</a> | <a href="/cookie-policy/">Cookie Policy</a>
	</div>
	</div><!-- /.site-footer__inner -->
</footer>
<?php
/**
 * Vitality Medical Marketing Group site-credit badge — same self-relocating component used on
 * mollurahairtransplant.com (and elsewhere in the fleet). Ships hidden (.vsc-hold), then
 * vsc-move finds the page's own <footer>, appends itself there (or into a distinct-background
 * "band" inside it if one exists — this theme's footer has none, so it lands as the footer's
 * last child), auto-picks white/dark text via a real contrast check against the footer's actual
 * background color, then reveals. Verbatim script/CSS from the reference implementation, not
 * reauthored — only the href's ref/utm_source params are Skyline-specific.
 */
?>
<style id="vsc-css">.vsc-wrap{display:flex;width:100%;flex-basis:100%;margin:14px 0 0;box-sizing:border-box}.vsc-wrap[data-p="auto"],.vsc-wrap[data-p="footer"],.vsc-wrap[data-p="band"]{max-width:var(--vsc-max,1280px);margin-left:auto;margin-right:auto;padding:4px var(--vsc-gutter,24px) 24px}.vsc-hold{visibility:hidden}.vsc-wrap[data-a="center"]{justify-content:center}.vsc-wrap[data-a="right"]{justify-content:flex-end}.vsc-wrap[data-a="left"]{justify-content:flex-start}.vsc{--vsc-ez:cubic-bezier(.32,.72,0,1);display:inline-flex;align-items:center;gap:11px;text-decoration:none;color:inherit;font-family:inherit;position:relative;line-height:1.2;transition:color .6s var(--vsc-ez),opacity .6s var(--vsc-ez),transform .8s var(--vsc-ez),box-shadow .8s var(--vsc-ez)}.vsc:hover,.vsc:focus-visible{opacity:1;color:var(--vsc-acc,currentColor)}.vsc:focus-visible{outline:2px solid var(--vsc-acc,currentColor);outline-offset:3px;border-radius:6px}.vsc svg{display:block;overflow:visible}.vsc-k{font-size:9.5px;letter-spacing:.2em;text-transform:uppercase;opacity:.68;display:block;font-weight:600}.vsc-b{font-size:12.5px;font-weight:700;letter-spacing:-.005em;display:block;margin-top:3px;white-space:nowrap}.vsc-1{font-size:12.5px;font-weight:600;white-space:nowrap}.vsc-1 em{font-style:normal;font-weight:800}@media(prefers-reduced-motion:reduce){.vsc,.vsc *{transition:none!important;animation:none!important}}.vsc-pulse .vsc-beat{stroke-dasharray:34;stroke-dashoffset:0}.vsc-pulse:hover .vsc-beat{animation:vsctrace 1.15s var(--vsc-ez)}@keyframes vsctrace{0%{stroke-dashoffset:34;opacity:.3}55%{opacity:1}100%{stroke-dashoffset:0;opacity:1}}.vsc-pulse .vsc-lf{transform-origin:19px 7px;transition:transform .9s var(--vsc-ez)}.vsc-pulse:hover .vsc-lf{transform:scale(1.14) rotate(-8deg)}.vsc-pulse .vsc-ring{transition:transform .9s var(--vsc-ez)}.vsc-pulse:hover .vsc-ring{transform:rotate(180deg)}</style><div class="vsc-wrap vsc-hold" data-a="center" data-p="auto"><a class="vsc vsc-pulse" href="https://vitalitymmg.com/work/?ref=skylinecruises&#038;utm_source=skylinecruises.com&#038;utm_medium=referral&#038;utm_campaign=footer-credit&#038;utm_content=pulse" target="_blank" rel="noopener" aria-label="Website by Vitality Medical Marketing Group (opens in a new tab)" style="opacity:0.8;"><svg width="32" height="32" viewBox="0 0 24 24" aria-hidden="true"><circle class="vsc-ring" cx="12" cy="12" r="11" fill="none" stroke="currentColor" stroke-width="1.2" stroke-dasharray="2.6 3.4" opacity=".5"/><g transform="translate(12,12) scale(.8) translate(-12,-12)"><path class="vsc-beat" d="M2.2 14.2 H6.1 L7.5 10.4 L9.7 17.6 L11.3 14.2 H13.1 C15.3 14.2 16.7 12.2 17.3 9.3" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path class="vsc-lf" d="M17.2 9.6 Q 22.4 9.2 21.5 4.0 Q 16.2 4.4 17.2 9.6 Z" fill="currentColor"/></g></svg><span><span class="vsc-k">Website by</span><span class="vsc-b">Vitality Medical Marketing Group</span></span></a></div><noscript><style>.vsc-hold{visibility:visible}</style></noscript><script id="vsc-move">(function(){var S=null;function rgb(v){var m=String(v).match(/rgba?\(([^)]+)\)/);if(!m){return null;}var p=m[1].split(",").map(parseFloat);if(p.length>3){if(p[3]<0.1){return null;}}return p;}function lum(c){var v=[c[0],c[1],c[2]].map(function(x){x=x/255;return x<=0.03928?x/12.92:Math.pow((x+0.055)/1.055,2.4);});return 0.2126*v[0]+0.7152*v[1]+0.0722*v[2];}function fix(w){var a=w.querySelector("a.vsc");if(!a){return;}var bg=null,el=w;while(el){var c=rgb(getComputedStyle(el).backgroundColor);if(c){bg=c;break;}el=el.parentElement;}if(!bg){return;}var fg=rgb(getComputedStyle(a).color);if(!fg){return;}var L1=lum(fg),L2=lum(bg);var r=(Math.max(L1,L2)+0.05)/(Math.min(L1,L2)+0.05);if(r>=3){return;}w.style.color=L2<0.4?"#ffffff":"#111111";}function band(t){var best=null;var W=t.getBoundingClientRect().width;var els=t.querySelectorAll("*");for(var i=0;i<els.length;i++){var e=els[i];if(!rgb(getComputedStyle(e).backgroundColor)){continue;}var r=e.getBoundingClientRect();if(r.width<W*0.8){continue;}if(r.height<40){continue;}best=e;}return best;}function m(){var w=document.querySelector(".vsc-hold");if(!w){return;}var t=S?document.querySelector(S):null;var how=S?"attached":"footer";if(!t){var f=document.querySelectorAll("footer");if(f.length){t=f[f.length-1];var b=band(t);if(b){t=b;how="band";}}}if(t){if(!t.contains(w)){t.appendChild(w);w.setAttribute("data-p",how);}}fix(w);w.classList.remove("vsc-hold");}if(document.readyState!=="loading"){m();}else{document.addEventListener("DOMContentLoaded",m);}})();</script>
