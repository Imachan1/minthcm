
/**
 * draggableElement - element with mousedown event
 * draggedElement - element that is being moved
 *
 * Usage:
 * 1. assign element to draggableElement in mounted lifecycle hook
 * 2. add mousedown event to the element with dragStart as callback function
 * 3. assign element to draggedElement in mounted lifecycle hook (leave empty if it's the same element as draggableElement)
 */

 export default {
  data () {
    return {
      draggableElement: null,
      draggedElement: null,
      prevCursorPosition: { left: 0, top: 0 },
      prevScrollPosition: { x: 0, y: 0 }
    }
  },
  methods: {
    dragStart (e) {
      if (!this.draggableElement) {
        this.draggableElement = this.$el
      }
      if (!this.draggedElement) {
        this.draggedElement = this.draggableElement
      }
      let elementPosition = window.getComputedStyle(this.draggedElement).position
      if (elementPosition === 'static') {
        elementPosition = 'relative'
        this.draggedElement.style.position = elementPosition
        this.draggedElement.style.left = '0px'
        this.draggedElement.style.top = '0px'
      }
      this.prevCursorPosition = {
        left: e.clientX,
        top: e.clientY
      }
      if (elementPosition !== 'fixed') {
        this.prevScrollPosition = {
          x: window.scrollX,
          y: window.scrollY
        }
        window.addEventListener('scroll', this.onScrollHandler)
      }
      window.addEventListener('mouseup', this.dragEnd)
      window.addEventListener('mousemove', this.onMouseMoveHandler)
    },
    dragEnd () {
      window.removeEventListener('mouseup', this.dragEnd)
      window.removeEventListener('scroll', this.onScrollHandler)
      window.removeEventListener('mousemove', this.onMouseMoveHandler)
      this.validateVisibility()
    },
    onMouseMoveHandler (e) {
      const cursorPosition = {
        left: e.clientX,
        top: e.clientY
      }
      const computedPosition = {
        left: parseInt(this.draggedElement.style.left, 10) + cursorPosition.left - this.prevCursorPosition.left,
        top: parseInt(this.draggedElement.style.top, 10) + cursorPosition.top - this.prevCursorPosition.top
      }
      this.move(computedPosition.left, computedPosition.top)
      this.prevCursorPosition = cursorPosition
    },
    onScrollHandler () {
      const scrollPosition = {
        x: window.scrollX,
        y: window.scrollY
      }
      const computedPosition = {
        left: parseInt(this.draggedElement.style.left, 10) + scrollPosition.x - this.prevScrollPosition.x,
        top: parseInt(this.draggedElement.style.top, 10) + scrollPosition.y - this.prevScrollPosition.y
      }
      this.move(computedPosition.left, computedPosition.top)
      this.prevScrollPosition = scrollPosition
    },
    move (left = 0, top = 0) {
      this.draggedElement.style.left = left + 'px'
      this.draggedElement.style.top = top + 'px'
    },
    validateVisibility () {
      let { width, height } = window.getComputedStyle(this.draggableElement)
      let { left, top, position } = window.getComputedStyle(this.draggedElement)
      width = parseInt(width, 10)
      height = parseInt(height, 10)
      left = parseInt(left, 10)
      top = parseInt(top, 10)

      const widthBoundary = position === 'fixed' ? window.innerWidth : document.documentElement.scrollWidth
      const heightBoundary = position === 'fixed' ? window.innerHeight : document.documentElement.scrollHeight

      // Specifies the number of pixels that draggable element sticks out from the window edge
      const w = width >= 100 ? 100 : width
      const h = height >= 50 ? 50 : height

      if (left < w - width) {
        this.draggedElement.style.left = w - width + 'px'
      } else if (left > widthBoundary - w) {
        this.draggedElement.style.left = widthBoundary - w + 'px'
      }
      if (top < h - height) {
        this.draggedElement.style.top = h - height + 'px'
      } else if (top > heightBoundary - h) {
        this.draggedElement.style.top = heightBoundary - h + 'px'
      }
    }
  }
}
