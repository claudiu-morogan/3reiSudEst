/**
 * Animation Controller
 * IntersectionObserver-based scroll reveals and page transitions
 */

class AnimationController {
  constructor() {
    this.observers = new Map();
    this.init();
  }

  init() {
    this.initScrollReveal();
    this.initStaggerAnimations();
  }

  initScrollReveal() {
    const options = {
      root: null,
      rootMargin: '0px 0px -100px 0px',
      threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
        }
      });
    }, options);

    document.querySelectorAll('.reveal').forEach(el => {
      observer.observe(el);
    });

    this.observers.set('reveal', observer);
  }

  initStaggerAnimations() {
    const staggerContainers = document.querySelectorAll('.stagger-children');
    
    staggerContainers.forEach(container => {
      const children = container.children;
      Array.from(children).forEach((child, index) => {
        child.style.animationDelay = (index * 0.1 + 0.1) + 's';
      });
    });
  }

  initParallax(selector) {
    const element = document.querySelector(selector);
    if (!element) return;

    let ticking = false;

    const updateParallax = () => {
      const scrolled = window.pageYOffset;
      const rate = scrolled * 0.5;
      
      element.style.transform = 'translateY(' + rate + 'px)';
      ticking = false;
    };

    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(updateParallax);
        ticking = true;
      }
    }, { passive: true });
  }

  destroy() {
    this.observers.forEach(observer => observer.disconnect());
    this.observers.clear();
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.animationController = new AnimationController();
  });
} else {
  window.animationController = new AnimationController();
}
