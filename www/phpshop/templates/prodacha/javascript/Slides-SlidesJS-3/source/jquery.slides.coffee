# SlidesJS 3.0.4

# Documentation and examples http://slidesjs.com
# Support forum http://groups.google.com/group/slidesjs
# Created by Nathan Searles http://nathansearles.com

# Version: 3.0.4
# Updated: June 26th, 2013

# SlidesJS is an open source project, contribute at GitHub:
# https://github.com/nathansearles/Slides

# (c) 2013 by Nathan Searles

# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at

# http://www.apache.org/licenses/LICENSE-2.0

# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

(($, window, document) ->
  pluginName = "slidesjs"
  defaults =
    width: 940
      # Set the default width of the slideshow.
    height: 528
      # Set the default height of the slideshow.
    start: 1
      # Set the first slide in the slideshow.
    navigation:
      # Next and previous button settings.
      active: true
        # [boolean] Create next and previous buttons.
        # You can set to false and use your own next/prev buttons.
        # User defined next/prev buttons must have the following:
        # previous: class="slidesjs-previous slidesjs-navigation"
        # next: class="slidesjs-next slidesjs-navigation"
      effect: "slide"
        # [string] Can be either "slide" or "fade".
    pagination:
        # Pagination settings
      active: true
        # [boolean] Create pagination items.
        # You cannot use your own pagination.
      effect: "slide"
        # [string] Can be either "slide" or "fade".
    play:
        # Play and stop button setting.
      active: false
        # [boolean] Create the play and stop buttons.
        # You cannot use your own pagination.
      effect: "slide"
        # [string] Can be either "slide" or "fade".
      interval: 5000
        # [number] Time spent on each slide in milliseconds.
      auto: false
        # [boolean] Start playing the slideshow on load
      swap: true
        # [boolean] show/hide stop and play buttons
      pauseOnHover: false
        # [boolean] pause a playing slideshow on hover
      restartDelay: 2500
        # [number] restart delay on an inactive slideshow
    effect:
      slide:
        # Slide effect settings.
        speed: 500
          # [number] Speed in milliseconds of the slide animation.
      fade:
        speed: 300
          # [number] Speed in milliseconds of the fade animation.
        crossfade: true
          # [boolean] Cross-fade the transition
    callback:
      loaded: () ->
        # [function] Called when slides is loaded
      start: () ->
        # [function] Called when animation has started
      complete: () ->
        # [function] Called when animation is complete

  class Plugin
    constructor: (@element, options) ->
      @options = $.extend true, {}, defaults, options
      @_defaults = defaults
      @_name = pluginName
      @init()

  Plugin::init = ->
    $element = $(@element)
    @data = $.data this

    # Set data
    $.data this, "animating", false
    $.data this, "total", $element.children().not(".slidesjs-navigation", $element).length
    $.data this, "current", @options.start - 1
    $.data this, "vendorPrefix", @_getVendorPrefix()

    # Detect touch device
    if typeof TouchEvent != "undefined"
      $.data this, "touch", true
      # Set slide speed to half for touch
      @options.effect.slide.speed = this.options.effect.slide.speed / 2

    # Hide overflow
    $element.css overflow: "hidden"

    # Create container
    $element.slidesContainer = $element.children().not(".slidesjs-navigation", $element).wrapAll("<div class='slidesjs-container'>", $element).parent().css
      overflow: "hidden"
      position: "relative"

    # Create control div
    $(".slidesjs-container", $element)
    .wrapInner("<div class='slidesjs-control'>", $element)
    .children()

    # Setup control div
    $(".slidesjs-control", $element)
    .css
      position: "relative"
      left: 0

    # Setup slides
    $(".slidesjs-control", $element)
    .children()
    .addClass("slidesjs-slide")
    .css
      position: "absolute"
      top: 0
      left: 0
      width: "100%"
      zIndex: 0
      display: "none"
      webkitBackfaceVisibility: "hidden"

    # Assign an index to each slide
    $.each( $(".slidesjs-control", $element).children(), (i) ->
      $slide = $(this)
      $slide.attr("slidesjs-index", i)
    )

    if @data.touch
      # Bind touch events, if supported
      $(".slidesjs-control", $element).on("touchstart", (e) =>
        @_touchstart(e)
      )

      $(".slidesjs-control", $element).on("touchmove", (e) =>
        @_touchmove(e)
      )

      $(".slidesjs-control", $element).on("touchend", (e) =>
        @_touchend(e)
      )

    # Fades in slideshow, your slideshow ID must be display:none in your CSS
    $element.fadeIn 0

    # Update sets width/height of slideshow
    @update()

    # If touch device setup next slides
    @_setuptouch() if @data.touch

    # Fade in start slide
    $(".slidesjs-control", $element)
    .children(":eq(" + @data.current + ")")
    .eq(0)
    .fadeIn 0, ->
      $(this).css zIndex: 10

    if @options.navigation.active
      # Create next/prev buttons
      prevButton = $("<a>"
        class: "slidesjs-previous slidesjs-navigation"
        href: "#"
        title: "Previous"
        text: "Previous"
      ).appendTo($element)

      nextButton = $("<a>"
        class: "slidesjs-next slidesjs-navigation"
        href: "#"
        title: "Next"
        text: "Next"
      ).appendTo($element)

    # bind click events
    $(".slidesjs-next", $element).click (e) =>
      e.preventDefault()
      @stop(true)
      @next(@options.navigation.effect)

    $(".slidesjs-previous", $element).click (e) =>
      e.preventDefault()
      @stop(true)
      @previous(@options.navigation.effect)

    if @options.play.active
      playButton = $("<a>",
        class: "slidesjs-play slidesjs-navigation"
        href: "#"
        title: "Play"
        text: "Play"
      ).appendTo($element)

      stopButton = $("<a>",
        class: "slidesjs-stop slidesjs-navigation"
        href: "#"
        title: "Stop"
        text: "Stop"
      ).appendTo($element)

      playButton.click (e) =>
        e.preventDefault()
        @play(true)

      stopButton.click (e) =>
        e.preventDefault()
        @stop(true)

      if @options.play.swap
        stopButton.css
          display: "none"


    if @options.pagination.active
      # Create unordered list pagination
      pagination = $("<ul>"
        class: "slidesjs-pagination"
      ).appendTo($element)

      # Create a list item and anchor for each slide
      $.each(new Array(@data.total), (i) =>
        paginationItem = $("<li>"
          class: "slidesjs-pagination-item"
        ).appendTo(pagination)

        paginationLink = $("<a>"
          href: "#"
          "data-slidesjs-item": i
          html: i + 1
        ).appendTo(paginationItem)

        # bind click events
        paginationLink.click (e) =>
          e.preventDefault()
          # Stop play
          @stop(true)
          # Goto to selected slide
          @goto( ($(e.currentTarget).attr("data-slidesjs-item") * 1) + 1 )
      )

    # Bind update on browser resize
    $(window).bind("resize", () =>
      @update()
    )

    # Set start pagination item to active
    @_setActive()

    # Auto play slideshow
    if @options.play.auto
      @play()

    # Slides has loaded
    @options.callback.loaded(@options.start)

  # @_setActive()
  # Sets the active slide in the pagination
  Plugin::_setActive = (number) ->
    $element = $(@element)
    @data = $.data this

    # Get the current slide index
    current = if number > -1 then number else @data.current

    # Set active slide in pagination
    $(".active", $element).removeClass "active"
    $(".slidesjs-pagination li:eq(" + current + ") a", $element).addClass "active"

  # @update()
  # Update the slideshow size on browser resize
  Plugin::update = ->
    $element = $(@element)
    @data = $.data this

    # Hide all slides expect current
    $(".slidesjs-control", $element).children(":not(:eq(" + @data.current + "))").css
      display: "none"
      left: 0
      zIndex: 0

    # Get the new width and height
    width = $element.width()
    height = (@options.height / @options.width) * width

    # Store new width and height
    @options.width = width
    @options.height = height

    # Set new width and height
    $(".slidesjs-control, .slidesjs-container", $element).css
      width: width
      height: height

  # @next()
  # Next mechanics
  Plugin::next = (effect) ->
    $element = $(@element)
    @data = $.data this

    # Set the direction
    $.data this, "direction", "next"

    # Slides or fade effect
    if effect is undefined then effect = @options.navigation.effect
    if effect is "fade" then @_fade() else @_slide()

  # @previous()
  # Previous mechanics
  Plugin::previous = (effect) ->
    $element = $(@element)
    @data = $.data this

    # Set the direction
    $.data this, "direction", "previous"

    # Slides or fade effect
    if effect is undefined then effect = @options.navigation.effect
    if effect is "fade" then @_fade() else @_slide()

  # @goto()
  # Pagination mechanics
  Plugin::goto = (number) ->
    $element = $(@element)
    @data = $.data this

    # Set effect to default if not defined
    if effect is undefined then effect = @options.pagination.effect

    # Error correction if slide doesn't exists
    if number > @data.total
      number = @data.total
    else if number < 1
      number = 1

    if typeof number is "number"
      if effect is "fade" then @_fade(number) else @_slide(number)
    else if typeof number is "string"
      if number is "first"
        if effect is "fade" then @_fade(0) else @_slide(0)
      else if number is "last"
        if effect is "fade" then @_fade(@data.total) else @_slide(@data.total)

  # @_setuptouch()
  # Setup slideshow for touch
  Plugin::_setuptouch = () ->
    $element = $(@element)
    @data = $.data this

    # Define slides control
    slidesControl = $(".slidesjs-control", $element)

    # Get next/prev slides around current slide
    next = @data.current + 1
    previous = @data.current - 1

    # Create the loop
    previous = @data.total - 1 if previous < 0
    next = 0 if next > @data.total - 1

    # By default next/prev slides are hidden, show them when on touch device
    slidesControl.children(":eq(" + next + ")").css
      display: "block"
      left: @options.width

    slidesControl.children(":eq(" + previous + ")").css
      display: "block"
      left: -@options.width

  # @_touchstart()
  # Start touch
  Plugin::_touchstart = (e) ->
    $element = $(@element)
    @data = $.data this
    touches = e.originalEvent.touches[0]

    # Setup the next and previous slides for swiping
    @_setuptouch()

    # Start touch timer
    $.data this, "touchtimer", Number(new Date())

    # Set touch position
    $.data this, "touchstartx", touches.pageX
    $.data this, "touchstarty", touches.pageY

    # Stop event from bubbling up
    e.stopPropagation()

  # @_touchend()
  # Animates the slideshow when touch is complete
  Plugin::_touchend = (e) ->
    $element = $(@element)
    @data = $.data this
    touches = e.originalEvent.touches[0]

    # Define slides control
    slidesControl = $(".slidesjs-control", $element)

    # Slide has been dragged to the right, goto previous slide
    if slidesControl.position().left > @options.width * 0.5 || slidesControl.position().left > @options.width * 0.1 && (Number(new Date()) - @data.touchtimer < 250)
      $.data this, "direction", "previous"
      @_slide()
    # Slide has been dragged to the left, goto next slide
    else if slidesControl.position().left < -(@options.width * 0.5) || slidesControl.position().left < -(@options.width * 0.1) && (Number(new Date()) - @data.touchtimer < 250)
      $.data this, "direction", "next"
      @_slide()
    else
      # Slide has not been dragged far enough, animate back to 0 and reset
        # Get the browser's vendor prefix
        prefix = @data.vendorPrefix

        # Create CSS3 styles based on vendor prefix
        transform = prefix + "Transform"
        duration = prefix + "TransitionDuration"
        timing = prefix + "TransitionTimingFunction"

        # Set CSS3 styles
        slidesControl[0].style[transform] = "translateX(0px)"
        slidesControl[0].style[duration] = @options.effect.slide.speed * 0.85 + "ms"

    # Rest slideshow
    slidesControl.on "transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", =>
        # Get the browser's vendor prefix
        prefix = @data.vendorPrefix

        # Create CSS3 styles based on vendor prefix
        transform = prefix + "Transform"
        duration = prefix + "TransitionDuration"
        timing = prefix + "TransitionTimingFunction"

        # Set CSS3 styles
        slidesControl[0].style[transform] = ""
        slidesControl[0].style[duration] = ""
        slidesControl[0].style[timing] = ""

    # Stop event from bubbling up
    e.stopPropagation()

  # @_touchmove()
  # Moves the slide on touch
  Plugin::_touchmove = (e) ->
    $element = $(@element)
    @data = $.data this
    touches = e.originalEvent.touches[0]

    # Get the browser's vendor prefix
    prefix = @data.vendorPrefix

    # Define slides control
    slidesControl = $(".slidesjs-control", $element)

    # Create CSS3 styles based on vendor prefix
    transform = prefix + "Transform"

    # Check if user is trying to scroll vertically
    $.data this, "scrolling", Math.abs(touches.pageX - @data.touchs