/*!
 * WPBakery Page Builder v6.0.0 (https://wpbakery.com)
 * Copyright 2011-2024 Michael M, WPBakery
 * License: Commercial. More details: http://go.wpbakery.com/licensing
 */

// jscs:disable
// jshint ignore: start

window.vc || (window.vc = {}),
    function() {
        "use strict";
        vc.templateOptions = {
            default: {
                evaluate: /<%([\s\S]+?)%>/g,
                interpolate: /<%=([\s\S]+?)%>/g,
                escape: /<%-([\s\S]+?)%>/g
            },
            custom: {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                escape: /\{\{([^\}]+?)\}\}(?!\})/g
            }
        };

        function escapeChar(match) {
            return "\\" + escapes[match]
        }
        var noMatch = /(.)^/,
            escapes = {
                "'": "'",
                "\\": "\\",
                "\r": "r",
                "\n": "n",
                "\u2028": "u2028",
                "\u2029": "u2029"
            },
            escapeRegExp = /\\|'|\r|\n|\u2028|\u2029/g;
        vc.template = function(text, settings) {
            settings = _.defaults({}, settings, vc.templateOptions.default);
            var render, matcher = RegExp([(settings.escape || noMatch).source, (settings.interpolate || noMatch).source, (settings.evaluate || noMatch).source].join("|") + "|$", "g"),
                index = 0,
                source = "__p+='";
            text.replace(matcher, function(match, escape, interpolate, evaluate, offset) {
                return source += text.slice(index, offset).replace(escapeRegExp, escapeChar), index = offset + match.length, escape ? source += "'+\n((__t=(" + escape + "))==null?'':_.escape(__t))+\n'" : interpolate ? source += "'+\n((__t=(" + interpolate + "))==null?'':__t)+\n'" : evaluate && (source += "';\n" + evaluate + "\n__p+='"), match
            }), source += "';\n", source = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + (source = settings.variable ? source : "with(obj||{}){\n" + source + "}\n") + "return __p;\n";
            try {
                render = new Function(settings.variable || "obj", "_", source)
            } catch (e) {
                throw e.source = source, e
            }

            function template(data) {
                return render.call(this, data, _)
            }
            matcher = settings.variable || "obj";
            return template.source = "function(" + matcher + "){\n" + source + "}", template
        }
    }(),
    function($) {
        "use strict";
        _.isUndefined(window.vc) && (window.vc = {}), window.Vc_postSettingsEditor = Backbone.View.extend({
            $editor: !1,
            sel: "",
            mode: "",
            is_focused: !1,
            ace_enabled: !1,
            initialize: function(sel) {
                sel && 0 < sel.length && (this.sel = sel), this.ace_enabled = !0
            },
            aceEnabled: function() {
                return this.ace_enabled && window.ace && window.ace.edit
            },
            setEditor: function(value) {
                if (!this.missingUnfilteredHtml()) return this.aceEnabled() ? this.setEditorAce(value) : this.setEditorTextarea(value), this.$editor
            },
            missingUnfilteredHtml: function() {
                return $("#" + this.sel).hasClass("wpb_missing_unfiltered_html")
            },
            focus: function() {
                var count;
                this.is_focused && (this.aceEnabled() ? (this.$editor.focus(), count = this.$editor.session.getLength(), this.$editor.gotoLine(count, this.$editor.session.getLine(count - 1).length)) : this.$editor.focus())
            },
            setEditorAce: function(value) {
                this.$editor || (this.$editor = ace.edit(this.sel), this.$editor.getSession().setMode("ace/mode/" + this.mode), this.$editor.setTheme("ace/theme/chrome")), this.$editor.setValue(value), this.$editor.clearSelection(), this.is_focused && this.$editor.focus();
                value = this.$editor.getSession().getLength();
                return this.$editor.gotoLine(value, this.$editor.getSession().getLine(value - 1).length), this.$editor
            },
            setEditorTextarea: function(value) {
                return this.$editor || (this.$editor = $("<textarea></textarea>").css({
                    width: "100%",
                    height: "100%",
                    minHeight: "300px"
                }), $("#" + this.sel).empty().append(this.$editor).css({
                    overflowLeft: "hidden",
                    width: "100%",
                    height: "100%"
                })), this.$editor.val(value), this.is_focused && this.$editor.focus(), this.$editor.parent().css({
                    overflow: "auto"
                }), this.$editor
            },
            setSize: function() {
                var height = $(window).height() - 380;
                this.aceEnabled() ? $("#" + this.sel).css({
                    height: height,
                    minHeight: height
                }) : (this.$editor.parent().css({
                    height: height,
                    minHeight: height
                }), this.$editor.css({
                    height: "98%",
                    width: "98%"
                }))
            },
            setSizeResizable: function() {
                var $editor = $("#" + this.sel);
                this.aceEnabled() ? $editor.css({
                    height: "30vh",
                    minHeight: "30vh"
                }) : (this.$editor.parent().css({
                    height: "30vh",
                    minHeight: "30vh"
                }), this.$editor.css({
                    height: "98%",
                    width: "98%"
                }))
            },
            getEditor: function() {
                return this.$editor
            },
            getValue: function() {
                return this.aceEnabled() ? this.$editor.getValue() : this.$editor.val()
            }
        })
    }(window.jQuery),
    function() {
        "use strict";
        window.Backbone.View.vcExtendUI = function(object) {
            var newObject = this.extend(object);
            return newObject.prototype._vcUIEventsHooks || (newObject.prototype._vcUIEventsHooks = []), object.uiEvents && newObject.prototype._vcUIEventsHooks.push(object.uiEvents), newObject
        }, window.vc.View = Backbone.View.extend({
            delegateEvents: function() {
                vc.View.__super__.delegateEvents.call(this), this._vcUIEventsHooks && this._vcUIEventsHooks.length && _.each(this._vcUIEventsHooks, function(events) {
                    _.isObject(events) && _.each(events, function(methods, e) {
                        _.isString(methods) && _.each(methods.split(/\s+/), function(method) {
                            this.on(e, this[method], this)
                        }, this)
                    }, this)
                }, this)
            }
        })
    }(), window.vc || (window.vc = {}),
    function($) {
        window.vc.seo_utils = {
            getTextContent: function(data) {
                data = data.replace(/\s*\bdata-vcv-[^"<>]+"[^"<>]+"+/g, "").replace(/<!--\[vcvSourceHtml]/g, "").replace(/\[\/vcvSourceHtml]-->/g, "").replace(/<\//g, " </");
                for (var documentFragment = document.createRange().createContextualFragment(data), helper = documentFragment.querySelector("style, script, noscript, meta, title, #vc_no-content-helper, .vc_controls"); helper;) helper.parentNode.removeChild(helper), helper = documentFragment.querySelector("style, script, noscript, meta, title, #vc_no-content-helper, .vc_controls");
                return documentFragment && documentFragment.textContent && documentFragment.textContent.trim()
            },
            createMeasurementElement: function() {
                var hiddenElement = document.createElement("div");
                return hiddenElement.id = "vc-measurement-element", hiddenElement.style.position = "absolute", hiddenElement.style.left = "-9999em", hiddenElement.style.top = 0, hiddenElement.style.height = 0, hiddenElement.style.overflow = "hidden", hiddenElement.style.fontFamily = "arial, sans-serif", hiddenElement.style.fontSize = "20px", hiddenElement.style.fontWeight = "400", document.body.appendChild(hiddenElement), hiddenElement
            },
            measureTextWidth: function(text) {
                var element = document.getElementById("vc-measurement-element");
                return (element = element || this.createMeasurementElement()).innerHTML = text, element.offsetWidth
            },
            findKeyphrase: function(text, keyphrase) {
                text = text.toLowerCase();
                keyphrase = (keyphrase = keyphrase.trim().toLowerCase()).replace(/[.*+?^${}()|[\]\\]/g, "\\$&"), keyphrase = new RegExp("\\b" + keyphrase + "\\b", "gi"), text = Array.from(text.matchAll(keyphrase));
                return text.length ? {
                    found: !0,
                    count: text.length,
                    positions: text.map(function(match) {
                        return match.index
                    })
                } : {
                    found: !1,
                    count: 0,
                    positions: []
                }
            },
            slugify: function(text) {
                return text.toString().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "")
            },
            findKeyphraseInSlug: function(slug, keyphrase) {
                keyphrase = this.slugify(keyphrase), slug = this.slugify(slug), keyphrase = keyphrase.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"), slug = slug.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"), keyphrase = new RegExp("\\b" + keyphrase.split("-").join("\\b-\\b") + "\\b", "gi"), slug = Array.from(slug.matchAll(keyphrase));
                return 0 < slug.length ? {
                    found: !0,
                    count: slug.length,
                    positions: slug.map(function(match) {
                        return match.index
                    })
                } : {
                    found: !1,
                    count: 0,
                    positions: []
                }
            },
            findKeyphraseInAltTag: function($images, keyphrase) {
                var totalImages = $images.length,
                    imagesWithKeyphrase = 0;
                return keyphrase = keyphrase.trim().toLowerCase(), $images.each(function() {
                    var altText = $(this).attr("alt");
                    altText && altText.toLowerCase().includes(keyphrase) && imagesWithKeyphrase++
                }), {
                    percentage: imagesWithKeyphrase / totalImages * 100,
                    imagesWithKeyphrase: imagesWithKeyphrase
                }
            },
            findKeyphraseDensity: function(text, keyphrase) {
                keyphrase = keyphrase.trim().toLowerCase();
                var totalWords = text.trim().split(/\s+/).length,
                    keyphrase = new RegExp("\\b" + keyphrase + "\\b", "gi");
                return {
                    keyphraseOccurrences: text.match(keyphrase) || [],
                    advisedMinOccurrences: Math.ceil(.005 * totalWords),
                    advisedMaxOccurrences: Math.ceil(.03 * totalWords)
                }
            },
            getParagraphs: function(data) {
                var data = data.find("p"),
                    preventedSelectors = [".vc_ui-help-block"];
                return data = (data = (data = data.filter(function(index, element) {
                    var $paragraph = $(element);
                    return !preventedSelectors.some(function(selector) {
                        return $paragraph.is(selector)
                    })
                })).filter(function(index, element) {
                    return 0 < $(element).text().trim().length
                })).filter(function(index, element) {
                    return !(1 === $(element).contents().length && 1 === $(element).children("a").length)
                })
            },
            getSentences: function(text) {
                return text.split(/(?<!\w\.\w.)(?<![A-Z][a-z]\.)(?<=\.|\?)\s/)
            },
            hasConsecutiveSentences: function(text) {
                for (var sentences = this.getSentences(text), consecutiveCount = 1, i = 1; i < sentences.length; i++)
                    if (sentences[i].split(" ")[0] === sentences[i - 1].split(" ")[0]) {
                        if (3 <= ++consecutiveCount) return {
                            consecutiveCount: consecutiveCount,
                            state: !0
                        }
                    } else consecutiveCount = 1;
                return {
                    consecutiveCount: consecutiveCount,
                    state: !1
                }
            },
            getPassiveVoicePercentage: function(paragraphs) {
                var _this, totalSentences = 0,
                    passiveVoiceSentences = 0;
                return paragraphs.length && (_this = this, paragraphs.each(function(index, element) {
                    element = _this.getSentences($(element).text());
                    totalSentences += element.length, element.forEach(function(sentence) {
                        _this.hasPassiveVoice(sentence) && passiveVoiceSentences++
                    })
                })), (totalSentences ? passiveVoiceSentences / totalSentences * 100 : 0).toFixed(2)
            },
            hasPassiveVoice: function(text) {
                return /\b(am|are|is|was|were|been|being)\s+[^.!?]*\b(by)\b/.test(text)
            },
            getWordsCount: function(text) {
                text = text.split(/\s/g);
                return (text = (text = text.reduce(function(result, word) {
                    word = word.replace(new RegExp("([ \\–\\-\\(\\)_\\[\\]’‘“”〝〞〟‟„\"'.?!:;,¿¡«»‹›—×+&۔؟،؛。｡！‼？⁇⁉⁈‥…・ー、〃〄〆〇〈〉《》「」『』【】〒〓〔〕〖〗〘〙〚〛〜〝〞〟〠〶〼〽｛｝｜～｟｠｢｣､［］･￥＄％＠＆＇（）＊／：；＜＞＼\\<>])", "g"), " $1 ");
                    return result.concat(word.split(" "))
                }, [])).filter(function(word) {
                    return "" !== word.trim()
                })).length
            },
            getTextSectionCount: function(htmlString) {
                var htmlString = (new DOMParser).parseFromString(htmlString, "text/html").body.childNodes,
                    results = [];
                return htmlString.forEach(function(node) {
                    node.nodeType === Node.ELEMENT_NODE && (node = function(node) {
                        var paragraphElements = node.querySelectorAll("p"),
                            wordCount = 0,
                            node = function(node) {
                                return node.querySelectorAll("h1, h2, h3, h4, h5, h6").length
                            }(node);
                        return paragraphElements.forEach(function(paragraph) {
                            return wordCount += (paragraph.textContent || "").trim().split(/\s+/).length
                        }), {
                            wordCount: wordCount,
                            subheadingCount: node
                        }
                    }(node), results.push(node))
                }), results
            }
        }
    }(window.jQuery), window.vc || (window.vc = {}),
    function($) {
        "use strict";
        var SeoStorage = Backbone.Model.extend({
            defaults: {
                formData: {
                    keyphrase: "",
                    title: "",
                    description: "",
                    slug: "",
                    isUsedKeyphrase: ""
                },
                results: []
            },
            setResults: function(item, type, state) {
                var _this, data, currentState = this.get(state);
                "focus-keyphrase" === type && (type = "keyphrase", item) && item !== this.get(state).keyphrase && (_this = this, data = {
                    action: "wpb_seo_check_key_phrase",
                    key_phrase: item,
                    post_id: window.vc_post_id,
                    _vcnonce: window.vcAdminNonce
                }, $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: data
                }).done(function(response) {
                    response.success && (currentState.isUsedKeyphrase = response.data, _this.set(state, currentState), _this.trigger("formData", "change", currentState))
                }).fail(function(response) {
                    console.error("Failed to get the previously used keyphrase response: " + response)
                })), currentState[type] = item, this.set(state, currentState), "formData" === state && this.trigger("formData", "change", currentState)
            },
            updateResult: function(state, title, description) {
                var item = {
                        state: state,
                        title: title,
                        description: description
                    },
                    state = this.get("results").slice(),
                    title = state.findIndex(function(result) {
                        return result.title === item.title
                    }); - 1 !== title ? (state[title] = item, this.trigger("resultChanged", "update", item)) : (state.push(item), this.trigger("resultChanged", "add", item)), this.set("results", state)
            },
            resetResult: function(title) {
                var resetResults = this.get("results").filter(function(result) {
                    return result.title !== title
                });
                this.set("results", resetResults)
            }
        });
        vc.seo_storage = new SeoStorage
    }(window.jQuery), window.vc || (window.vc = {}),
    function($) {
        "use strict";
        var utils = vc.seo_utils,
            checkData = {
                focusKeyphrase: {
                    title: i18nLocale.focusKeywordTitle,
                    checkMethod: "checkFocusKeyphrase",
                    conditions: []
                },
                descriptionLength: {
                    title: i18nLocale.seoDescription,
                    checkMethod: "checkDescriptionLength",
                    conditions: []
                },
                titleWidth: {
                    title: i18nLocale.seoTitle,
                    checkMethod: "checkTitleWidth",
                    conditions: []
                },
                postTextLength: {
                    title: i18nLocale.textLength,
                    checkMethod: "checkPostTextLength",
                    conditions: []
                },
                images: {
                    title: i18nLocale.images,
                    checkMethod: "checkImages",
                    conditions: ["content"]
                },
                inboundLinks: {
                    title: i18nLocale.internalLinks,
                    checkMethod: "checkInboundLinks",
                    conditions: ["content"]
                },
                outboundLinks: {
                    title: i18nLocale.outboundLinks,
                    checkMethod: "checkOutboundLinks",
                    conditions: ["content"]
                },
                consecutiveSentences: {
                    title: i18nLocale.consecutiveSentences,
                    checkMethod: "checkForConsecutiveSentences",
                    conditions: ["text", "content"]
                },
                subheadingDistribution: {
                    title: i18nLocale.subheadingDistribution,
                    checkMethod: "checkSubheadingDistribution",
                    conditions: ["text", "content"]
                },
                paragraphLength: {
                    title: i18nLocale.paragraphLength,
                    checkMethod: "checkParagraphLength",
                    conditions: ["text", "content"]
                },
                passiveVoicePercentage: {
                    title: i18nLocale.passiveVoice,
                    checkMethod: "checkPassiveVoicePercentage",
                    conditions: ["text", "content"]
                },
                checkSentenceLength: {
                    title: i18nLocale.sentenceLength,
                    checkMethod: "checkSentenceLength",
                    conditions: ["text", "content"]
                },
                keyphraseInTitle: {
                    title: i18nLocale.keyphraseInTitleText,
                    checkMethod: "checkKeyphraseInTitle",
                    conditions: ["title", "keyphrase"]
                },
                keyphraseInDescription: {
                    title: i18nLocale.keyphraseInDescriptionText,
                    checkMethod: "checkKeyphraseInDescription",
                    conditions: ["description", "keyphrase"]
                },
                keyphraseInSlug: {
                    title: i18nLocale.keyphraseInSlug,
                    checkMethod: "checkKeyphraseInSlug",
                    conditions: ["slug", "keyphrase"]
                },
                keyphraseInImages: {
                    title: i18nLocale.imageKeyphrase,
                    checkMethod: "checkKeyphraseInImages",
                    conditions: ["content", "keyphrase", "images"]
                },
                keyphraseDensity: {
                    title: i18nLocale.keyphraseDensity,
                    checkMethod: "checkKeyphraseDensity",
                    conditions: ["content", "keyphrase", "text"]
                },
                keyphraseInIntroduction: {
                    title: i18nLocale.keyphraseInIntroductionText,
                    checkMethod: "checkKeyphraseInIntroduction",
                    conditions: ["content", "keyphrase", "text"]
                },
                previouslyUsedKeyphrase: {
                    title: i18nLocale.previouslyUsedKeyphrase,
                    checkMethod: "checkPreviouslyUsedKeyphrase",
                    conditions: ["keyphrase"]
                }
            };
        vc.seo_checks = {
            $wpbContentWrapper: null,
            analyzeContent: function($contentWrapper) {
                this.$wpbContentWrapper = $contentWrapper;
                var key, $contentWrapper = this.$wpbContentWrapper.find(">div:not(#vc_no-content-helper)"),
                    $text = $contentWrapper.find("p"),
                    $images = $contentWrapper.find("img"),
                    formData = vc.seo_storage.get("formData"),
                    conditions = {
                        content: $contentWrapper.length,
                        text: $text.length,
                        images: $images.length,
                        keyphrase: formData.keyphrase,
                        title: formData.title,
                        description: formData.description,
                        slug: formData.slug
                    };
                for (key in checkData) checkData[key] && (! function(key) {
                    return checkData[key].conditions.every(function(condition) {
                        return conditions[condition]
                    })
                }(key) ? vc.seo_storage.resetResult(checkData[key].title) : this[checkData[key].checkMethod](checkData[key].title))
            },
            checkTitleWidth: function(title) {
                var state = "problems",
                    description = i18nLocale.seoTitleWidthTooLong,
                    titleWidth = utils.measureTextWidth(vc.seo_storage.get("formData").title);
                titleWidth ? titleWidth < 600 && (state = "success", description = i18nLocale.goodJob) : description = i18nLocale.seoTitleEmpty, vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkFocusKeyphrase: function(title) {
                var state = "problems",
                    description = i18nLocale.noFocusKeyword;
                vc.seo_storage.get("formData").keyphrase && (state = "success", description = i18nLocale.goodJob), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkDescriptionLength: function(title) {
                var state = "problems",
                    description = "";
                vc.seo_storage.get("formData").description.length ? vc.seo_storage.get("formData").description.length < 120 ? (state = "warnings", description = i18nLocale.seoDescriptionTooShort) : 120 < vc.seo_storage.get("formData").description.length && vc.seo_storage.get("formData").description.length <= 156 ? (state = "success", description = i18nLocale.wellDone) : 156 < vc.seo_storage.get("formData").description.length && (state = "warnings", description = i18nLocale.seoDescriptionTooLong) : description = i18nLocale.seoDescriptionEmpty, vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseInTitle: function(title) {
                var state = "problems",
                    description = i18nLocale.keyphraseInTitleEmpty.replace("%1$s", vc.seo_storage.get("formData").keyphrase),
                    seoTitle = vc.seo_storage.get("formData").title.toLowerCase(),
                    seoKeyphrase = vc.seo_storage.get("formData").keyphrase.trim().toLowerCase(),
                    result = utils.findKeyphrase(vc.seo_storage.get("formData").title, vc.seo_storage.get("formData").keyphrase),
                    seoTitle = 0 === seoTitle.indexOf(seoKeyphrase);
                result.found && (description = seoTitle ? (state = "success", i18nLocale.goodJob) : (state = "warnings", i18nLocale.keyphraseInTitleWarn)), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseInDescription: function(title) {
                var state = "problems",
                    description = i18nLocale.keyphraseInDescriptionEmpty;
                utils.findKeyphrase(vc.seo_storage.get("formData").description, vc.seo_storage.get("formData").keyphrase).found && (state = "success", description = i18nLocale.keyphraseInDescriptionSuccess), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseInSlug: function(title) {
                var state = "warnings",
                    description = i18nLocale.keyphraseInSlugProblem;
                utils.findKeyphraseInSlug(vc.seo_storage.get("formData").slug, vc.seo_storage.get("formData").keyphrase).found && (state = "success", description = i18nLocale.greatWork), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseInImages: function(title) {
                var state = "success",
                    description = "",
                    imagesData = utils.findKeyphraseInAltTag(this.images, vc.seo_storage.get("formData").keyphrase),
                    percentage = imagesData.percentage,
                    imagesData = imagesData.imagesWithKeyphrase,
                    description = 4 < this.images.length ? 30 <= percentage && percentage <= 70 ? i18nLocale.goodJob : (70 < percentage ? (state = "warnings", i18nLocale.imageKeyphraseTooMuch) : (state = "warnings", i18nLocale.imageKeyphraseNotEnough)).replace("%1$s", this.images.length).replace("%2$s", imagesData) : 0 < imagesData ? i18nLocale.goodJob : (state = "warnings", i18nLocale.imageKeyphraseMissing);
                vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseDensity: function(title) {
                var state = "success",
                    description = "",
                    keyphraseInText = utils.findKeyphraseDensity(this.textContent, vc.seo_storage.get("formData").keyphrase),
                    keyphraseOccurrences = keyphraseInText.keyphraseOccurrences,
                    advisedMinOccurrences = keyphraseInText.advisedMinOccurrences,
                    keyphraseInText = keyphraseInText.advisedMaxOccurrences,
                    description = keyphraseOccurrences.length < advisedMinOccurrences ? (state = "problems", i18nLocale.keyphraseDensityNotEnough.replace("%1$s", keyphraseOccurrences.length).replace("%2$s", advisedMinOccurrences)) : keyphraseOccurrences.length >= advisedMinOccurrences && keyphraseOccurrences.length <= keyphraseInText ? i18nLocale.keyphraseDensitySuccess.replace("%1$s", keyphraseOccurrences.length) : (state = "problems", i18nLocale.keyphraseDensityTooMuch.replace("%1$s", keyphraseOccurrences.length).replace("%2$s", keyphraseInText));
                vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkInboundLinks: function(title) {
                var inboundLinks = [],
                    goodJob = i18nLocale.goodJob,
                    noInboundLinksDescription = i18nLocale.noInternalLinks,
                    $links = this.$wpbContentWrapper.find(">div:not(#vc_no-content-helper) a:not([class*='vc_control'])"),
                    inboundLinksState = "success";
                $.each($links, function(i, link) {
                    window.location.host === link.host && inboundLinks.push(link)
                }), inboundLinks.length || (inboundLinksState = "problems", goodJob = noInboundLinksDescription), vc.seo_storage.updateResult(inboundLinksState, title, goodJob, "results")
            },
            checkOutboundLinks: function(title) {
                var outboundLinks = [],
                    goodJob = i18nLocale.goodJob,
                    noOutboundLinksDescription = i18nLocale.noOutboundLinks,
                    $links = this.$wpbContentWrapper.find(">div:not(#vc_no-content-helper) a:not([class*='vc_control'])"),
                    outboundLinksState = "success";
                $.each($links, function(i, link) {
                    window.location.host !== link.host && outboundLinks.push(link)
                }), outboundLinks.length || (outboundLinksState = "problems", goodJob = noOutboundLinksDescription), vc.seo_storage.updateResult(outboundLinksState, title, goodJob, "results")
            },
            checkImages: function(title) {
                var state = "problems",
                    description = i18nLocale.noImages,
                    images = this.$wpbContentWrapper.find(">div:not(#vc_no-content-helper) img");
                (this.images = images).length && (state = "success", description = i18nLocale.goodJob), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkPostTextLength: function(title) {
                var state = "problems",
                    description = i18nLocale.textLengthLess,
                    postContent = this.$wpbContentWrapper.html(),
                    textContent = utils.getTextContent(postContent),
                    textContent = (this.textContent = textContent).split(/\s+/).length;
                (textContent = 1 === textContent && "" === utils.getTextContent(postContent).split(/\s+/)[0] ? 0 : textContent) < 200 ? description = description.replace("%1$s", textContent).replace("%2$s", "far below") : 200 <= textContent && textContent < 250 ? description = description.replace("%1$s", textContent).replace("%2$s", "below") : 250 <= textContent && textContent < 300 ? (description = description.replace("%1$s", textContent).replace("%2$s", "slightly below"), state = "warnings") : 300 <= textContent && (description = window.sprintf(i18nLocale.textLengthSuccess, textContent), state = "success"), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkKeyphraseInIntroduction: function(title) {
                var state = "problems",
                    description = i18nLocale.keyphraseInIntroductionEmpty,
                    firstParagraph = utils.getParagraphs(this.$wpbContentWrapper).first().text();
                utils.findKeyphrase(firstParagraph, vc.seo_storage.get("formData").keyphrase).found && (state = "success", description = i18nLocale.wellDone), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkPassiveVoicePercentage: function(title) {
                var paragraphs = utils.getParagraphs(this.$wpbContentWrapper),
                    paragraphs = utils.getPassiveVoicePercentage(paragraphs),
                    state = "problems",
                    description = window.sprintf(i18nLocale.passiveVoiceError, paragraphs + "%");
                paragraphs < 10 && (state = "success", description = i18nLocale.passiveVoiceSuccess), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkForConsecutiveSentences: function(title) {
                var hasConsecutiveSentences = utils.hasConsecutiveSentences(this.textContent),
                    state = hasConsecutiveSentences.state ? "problems" : "success",
                    description = i18nLocale.consecutiveSentencesSuccess;
                hasConsecutiveSentences.state && (description = i18nLocale.consecutiveSentencesFail.replace("%1$s", hasConsecutiveSentences.consecutiveCount)), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkParagraphLength: function(title) {
                var state = "success",
                    description = i18nLocale.paragraphLengthSuccess,
                    paragraphs = utils.getParagraphs(this.$wpbContentWrapper),
                    longParagraphsCount = 0;
                paragraphs.each(function(index, element) {
                    element = $(element).text();
                    150 < utils.getWordsCount(element) && longParagraphsCount++
                }), 0 < longParagraphsCount && (description = window.sprintf(i18nLocale.paragraphLengthError, longParagraphsCount), state = "problems"), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkSentenceLength: function(title) {
                var state = "success",
                    description = i18nLocale.great,
                    paragraphs = utils.getParagraphs(this.$wpbContentWrapper),
                    totalSentences = 0,
                    longSentencesCount = 0,
                    paragraphs = (paragraphs.each(function(index, paragraph) {
                        paragraph = utils.getSentences($(paragraph).text());
                        totalSentences += paragraph.length, paragraph.forEach(function(sentence) {
                            20 < utils.getWordsCount(sentence) && longSentencesCount++
                        })
                    }), longSentencesCount / totalSentences * 100);
                1 !== totalSentences && 25 < paragraphs && (description = window.sprintf(i18nLocale.sentenceLengthError, paragraphs.toFixed()), state = "problems"), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkSubheadingDistribution: function(title) {
                var contentHtmlString = this.$wpbContentWrapper.html(),
                    contentHtmlString = utils.getTextSectionCount(contentHtmlString),
                    state = "success",
                    description = i18nLocale.goodJob,
                    isNoHeadings = contentHtmlString.filter(function(section) {
                        return !section.subheadingCount && 300 < section.wordCount
                    }),
                    contentHtmlString = contentHtmlString.filter(function(section) {
                        return 1 === section.subheadingCount && 300 < section.wordCount
                    });
                isNoHeadings.length ? (state = "problems", description = i18nLocale.subheadingDistributionFail) : contentHtmlString.length && (state = "warnings", description = i18nLocale.subheadingDistributionWarn.replace("%s", contentHtmlString.length)), vc.seo_storage.updateResult(state, title, description, "results")
            },
            checkPreviouslyUsedKeyphrase: function(title) {
                var state = "success",
                    description = i18nLocale.previouslyUsedKeyphraseSuccess;
                vc.seo_storage.get("formData").isUsedKeyphrase && (state = "warnings", description = i18nLocale.previouslyUsedKeyphraseWarn), vc.seo_storage.updateResult(state, title, description, "results")
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        _.isUndefined(window.vc) && (window.vc = {}), $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                "script" === settings.dataType && !0 === settings.cache && (settings.cache = !1), "script" === settings.dataType && !1 === settings.async && (settings.async = !0)
            }
        }), vc.showSpinner = function() {
            $("#vc_logo").addClass("vc_ui-wp-spinner")
        }, vc.hideSpinner = function() {
            $("#vc_logo").removeClass("vc_ui-wp-spinner")
        }, $(document).ajaxSend(function(e, xhr, req) {
            req && req.data && "string" == typeof req.data && req.data.match(/vc_inline=true/) && vc.showSpinner()
        }).ajaxStop(function() {
            vc.hideSpinner()
        }), vc.active_panel = !1, vc.closeActivePanel = function(model) {
            if (!this.active_panel) return !1;
            (model && vc.active_panel.model && vc.active_panel.model.get("id") === model.get("id") || !model) && (vc.active_panel.model = null, this.active_panel.hide())
        }, vc.activePanelName = function() {
            return this.active_panel && this.active_panel.panelName ? this.active_panel.panelName : null
        }, vc.updateSettingsBadge = function() {
            var badge = $("#vc_post-settings-badge");
            vc.isShowBadge() ? badge.show() : badge.hide()
        }, vc.isShowBadge = function() {
            var isShowBadge = !1;
            return ["css", "js_header", "js_footer"].forEach(function(setting_name) {
                setting_name = vc["$custom_" + setting_name].val();
                setting_name && "" !== setting_name.trim() && (isShowBadge = !0)
            }), isShowBadge
        }, vc.ModalView = Backbone.View.extend({
            message_box_timeout: !1,
            events: {
                "hidden.bs.modal": "hide",
                "shown.bs.modal": "shown"
            },
            initialize: function() {
                _.bindAll(this, "setSize", "hide")
            },
            setSize: function() {
                var height = $(window).height() - 150;
                this.$content.css("maxHeight", height), this.trigger("setSize")
            },
            render: function() {
                return $(window).on("resize.ModalView", this.setSize), this.setSize(), vc.closeActivePanel(), this.$el.modal("show"), this
            },
            showMessage: function(text, type) {
                this.message_box_timeout && this.$el.find(".vc_message").remove() && window.clearTimeout(this.message_box_timeout), this.message_box_timeout = !1;
                var $message_box = $('<div class="vc_message type-' + type + '"></div>');
                this.$el.find(".vc_modal-body").prepend($message_box), $message_box.text(text).fadeIn(), this.message_box_timeout = window.setTimeout(function() {
                    $message_box.remove()
                }, 6e3)
            },
            hide: function() {
                $(window).off("resize.ModalView")
            },
            shown: function() {}
        }), vc.element_start_index = 0, vc.AddElementBlockView = vc.ModalView.extend({
            el: $("#vc_add-element-dialog"),
            prepend: !1,
            builder: "",
            events: {
                "click .vc_shortcode-link": "createElement",
                "keyup #vc_elements_name_filter": "filterElements",
                "hidden.bs.modal": "hide",
                "show.bs.modal": "buildFiltering",
                "click .wpb-content-layouts-container [data-filter]": "filterElements",
                "shown.bs.modal": "shown"
            },
            buildFiltering: function() {
                this.do_render = !1, item_selector = '[data-vc-ui-element="add-element-button"]', tag = this.model ? this.model.get("shortcode") : "vc_column", not_in = this._getNotIn(tag), $("#vc_elements_name_filter").val(""), this.$content.addClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", "*");
                var item_selector, tag, not_in, parent_selector, mapped = vc.getMapped(tag),
                    as_parent = !(!tag || _.isUndefined(mapped.as_parent)) && mapped.as_parent;
                _.isObject(as_parent) ? (parent_selector = [], _.isString(as_parent.only) && parent_selector.push(_.reduce(as_parent.only.replace(/\s/, "").split(","), function(memo, val) {
                    return memo + (_.isEmpty(memo) ? "" : ",") + '[data-element="' + val.trim() + '"]'
                }, "")), _.isString(as_parent.except) && parent_selector.push(_.reduce(as_parent.except.replace(/\s/, "").split(","), function(memo, val) {
                    return memo + ':not([data-element="' + val.trim() + '"])'
                }, "")), item_selector += parent_selector.join(",")) : not_in && (item_selector = not_in), tag && !_.isUndefined(mapped.allowed_container_element) && (mapped.allowed_container_element ? _.isString(mapped.allowed_container_element) && (item_selector += ":not([data-is-container=true]), [data-element=" + mapped.allowed_container_element + "]") : item_selector += ":not([data-is-container=true])"), this.$buttons.removeClass("vc_visible").addClass("vc_inappropriate"), $(item_selector, this.$content).removeClass("vc_inappropriate").addClass("vc_visible"), this.hideEmptyFilters()
            },
            hideEmptyFilters: function() {
                this.$el.find(".vc_filter-content-elements .active").removeClass("active"), this.$el.find(".vc_filter-content-elements > :first").addClass("active");
                var self = this;
                this.$el.find("[data-filter]").each(function() {
                    $($(this).data("filter") + ".vc_visible:not(.vc_inappropriate)", self.$content).length ? $(this).parent().show() : $(this).parent().hide()
                })
            },
            render: function(model, prepend) {
                return this.builder = new vc.ShortcodesBuilder, this.prepend = !!_.isBoolean(prepend) && prepend, this.place_after_id = !!_.isString(prepend) && prepend, this.model = !!_.isObject(model) && model, this.$content = this.$el.find('[data-vc-ui-element="panel-add-element-list"]'), this.$buttons = $('[data-vc-ui-element="add-element-button"]', this.$content), this.preventDoubleExecution = !1, vc.AddElementBlockView.__super__.render.call(this)
            },
            hide: function() {
                this.do_render && (this.show_settings && this.showEditForm(), this.exit())
            },
            showEditForm: function() {
                vc.edit_element_block_view.render(this.builder.last())
            },
            exit: function() {
                this.builder.render()
            },
            createElement: function(e) {
                var column_params, _this, i;
                if (!this.preventDoubleExecution) {
                    this.preventDoubleExecution = !0, this.do_render = !0, e.preventDefault(), e = $(e.currentTarget).data("tag"), row_inner_params = {}, !(column_params = {
                        width: "1/1"
                    }) === this.model && "vc_row" !== e ? (this.builder.create({
                        shortcode: "vc_row",
                        params: {}
                    }).create({
                        shortcode: "vc_column",
                        parent_id: this.builder.lastID(),
                        params: column_params
                    }), this.model = this.builder.last()) : !1 !== this.model && "vc_row" === e && (e += "_inner");
                    var row_inner_params = {
                        shortcode: e,
                        parent_id: !!this.model && this.model.get("id"),
                        params: "vc_row_inner" === e ? row_inner_params : {}
                    };
                    for (this.prepend ? (row_inner_params.order = 0, (shortcodeFirst = vc.shortcodes.findWhere({
                            parent_id: this.model.get("id")
                        })) && (row_inner_params.order = shortcodeFirst.get("order") - 1), vc.activity = "prepend") : this.place_after_id && (row_inner_params.place_after_id = this.place_after_id), this.builder.create(row_inner_params), i = this.builder.models.length - 1; 0 <= i; i--) this.builder.models[i].get("shortcode");
                    "vc_row" === e ? this.builder.create({
                        shortcode: "vc_column",
                        parent_id: this.builder.lastID(),
                        params: column_params
                    }) : "vc_row_inner" === e && (column_params = {
                        width: "1/1"
                    }, this.builder.create({
                        shortcode: "vc_column_inner",
                        parent_id: this.builder.lastID(),
                        params: column_params
                    }));
                    var shortcodeFirst = vc.getMapped(e);
                    _.isString(shortcodeFirst.default_content) && shortcodeFirst.default_content.length && (row_inner_params = this.builder.parse({}, shortcodeFirst.default_content, this.builder.last().toJSON()), _.each(row_inner_params, function(object) {
                        object.default_content = !0, this.builder.create(object)
                    }, this)), this.show_settings = !(_.isBoolean(shortcodeFirst.show_settings_on_create) && !1 === shortcodeFirst.show_settings_on_create), (_this = this).$el.one("hidden.bs.modal", function() {
                        _this.preventDoubleExecution = !1
                    }).modal("hide")
                }
            },
            _getNotIn: _.memoize(function(tag) {
                return '[data-vc-ui-element="add-element-button"]:not(' + _.reduce(vc.map, function(memo, shortcode) {
                    var separator = _.isEmpty(memo) ? "" : ",";
                    return _.isObject(shortcode.as_child) ? (_.isString(shortcode.as_child.only) && !_.contains(shortcode.as_child.only.replace(/\s/, "").split(","), tag) && (memo += separator + "[data-element=" + shortcode.base + "]"), _.isString(shortcode.as_child.except) && _.contains(shortcode.as_child.except.replace(/\s/, "").split(","), tag) && (memo += separator + "[data-element=" + shortcode.base + "]")) : !1 === shortcode.as_child && (memo += separator + "[data-element=" + shortcode.base + "]"), memo
                }, "") + ")"
            }),
            filterElements: function(e) {
                e.stopPropagation(), e.preventDefault();
                var e = $(e.currentTarget),
                    filter = '[data-vc-ui-element="add-element-button"]',
                    name_filter = $("#vc_elements_name_filter").val();
                this.$content.removeClass("vc_filter-all"), e.is("[data-filter]") ? ($(".wpb-content-layouts-container .isotope-filter .active", this.$content).removeClass("active"), e.parent().addClass("active"), filter += e = e.data("filter"), "*" === e ? this.$content.addClass("vc_filter-all") : this.$content.removeClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", e.replace(".js-category-", "")), $("#vc_elements_name_filter").val("")) : 0 < name_filter.length ? (filter += ":containsi('" + name_filter + "'):not('.vc_element-deprecated')", $(".wpb-content-layouts-container .isotope-filter .active", this.$content).removeClass("active"), this.$content.attr("data-vc-ui-filter", "name:" + name_filter)) : name_filter.length || ($('.wpb-content-layouts-container .isotope-filter [data-filter="*"]').parent().addClass("active"), this.$content.attr("data-vc-ui-filter", "*"), this.$content.addClass("vc_filter-all")), $(".vc_visible", this.$content).removeClass("vc_visible"), $(filter, this.$content).addClass("vc_visible")
            },
            shown: function() {
                vc.is_mobile || $("#vc_elements_name_filter").trigger("focus")
            }
        }), vc.AddElementBlockViewBackendEditor = vc.AddElementBlockView.extend({
            render: function(model, prepend) {
                return this.prepend = !!_.isBoolean(prepend) && prepend, this.place_after_id = !!_.isString(prepend) && prepend, this.model = !!_.isObject(model) && model, this.$content = this.$el.find('[data-vc-ui-element="panel-add-element-list"]'), this.$buttons = $('[data-vc-ui-element="add-element-button"]', this.$content), vc.AddElementBlockView.__super__.render.call(this)
            },
            createElement: function(e) {
                var that, row, column_params;
                this.preventDoubleExecution || (this.preventDoubleExecution = !0, e && e.preventDefault && e.preventDefault(), this.do_render = !0, e = $(e.currentTarget).data("tag"), column_params = !(column_params = {
                    width: "1/1"
                }) === this.model ? (row = vc.shortcodes.create({
                    shortcode: "vc_row",
                    params: {}
                }), column_params = vc.shortcodes.create({
                    shortcode: "vc_column",
                    params: column_params,
                    parent_id: row.id,
                    root_id: row.id
                }), "vc_row" !== e ? vc.shortcodes.create({
                    shortcode: e,
                    parent_id: column_params.id,
                    root_id: row.id
                }) : row) : "vc_row" === e ? (column_params = {
                    width: "1/1"
                }, row = vc.shortcodes.create({
                    shortcode: "vc_row_inner",
                    params: {},
                    parent_id: this.model.id,
                    order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()
                }), vc.shortcodes.create({
                    shortcode: "vc_column_inner",
                    params: column_params,
                    parent_id: row.id,
                    root_id: row.id
                })) : vc.shortcodes.create({
                    shortcode: e,
                    parent_id: this.model.id,
                    order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
                    root_id: this.model.get("root_id")
                }), this.show_settings = !(_.isBoolean(vc.getMapped(e).show_settings_on_create) && !1 === vc.getMapped(e).show_settings_on_create), this.model = column_params, this.model.get("shortcode"), (that = this).$el.one("hidden.bs.modal", function() {
                    that.preventDoubleExecution = !1
                }).modal("hide"))
            },
            showEditForm: function() {
                vc.edit_element_block_view.render(this.model)
            },
            exit: function() {},
            getFirstPositionIndex: function() {
                return --vc.element_start_index, vc.element_start_index
            }
        }), vc.PanelView = vc.View.extend({
            mediaSizeClassPrefix: "vc_media-",
            customMediaQuery: !0,
            panelName: "panel",
            draggable: !1,
            $body: !1,
            $tabs: !1,
            $content: !1,
            events: {
                "click [data-dismiss=panel]": "hide",
                "mouseover [data-transparent=panel]": "addOpacity",
                "click [data-transparent=panel]": "toggleOpacity",
                "mouseout [data-transparent=panel]": "removeOpacity",
                "click .vc_panel-tabs-link": "changeTab"
            },
            _vcUIEventsHooks: [{
                resize: "setResize"
            }],
            options: {
                startTab: 0
            },
            clicked: !1,
            showMessageDisabled: !0,
            initialize: function() {
                this.clicked = !1, this.$el.removeClass("vc_panel-opacity"), this.$body = $("body"), this.$content = this.$el.find(".vc_panel-body"), _.bindAll(this, "setSize", "fixElContainment", "changeTab", "setTabsSize"), this.on("show", this.setSize, this), this.on("setSize", this.setResize, this), this.on("render", this.resetMinimize, this)
            },
            toggleOpacity: function() {
                this.clicked = !this.clicked
            },
            addOpacity: function() {
                this.clicked || this.$el.addClass("vc_panel-opacity")
            },
            removeOpacity: function() {
                this.clicked || this.$el.removeClass("vc_panel-opacity")
            },
            message_box_timeout: !1,
            init: function() {},
            render: function() {
                return this.trigger("render"), this.trigger("afterRender"), this
            },
            show: function() {
                var $tabs;
                this.$el.hasClass("vc_active") || (vc.closeActivePanel(), this.init(), (vc.active_panel = this).clicked = !1, this.$el.removeClass("vc_panel-opacity"), ($tabs = this.$el.find(".vc_panel-tabs")).length && (this.$tabs = $tabs, this.setTabs()), this.$el.addClass("vc_active"), this.draggable ? this.initDraggable() : $(window).trigger("resize"), this.fixElContainment(), this.trigger("show"))
            },
            hide: function(e) {
                e && e.preventDefault && e.preventDefault(), this.model && (this.model = null), vc.active_panel = !1, this.$el.removeClass("vc_active")
            },
            content: function() {
                return this.$el.find(".panel-body")
            },
            setResize: function() {
                this.customMediaQuery && this.setMediaSizeClass()
            },
            setMediaSizeClass: function() {
                var modalWidth = this.$el.width(),
                    classes = {
                        xs: !0,
                        sm: !1,
                        md: !1,
                        lg: !1
                    };
                525 <= modalWidth && (classes.sm = !0), 745 <= modalWidth && (classes.md = !0), 945 <= modalWidth && (classes.lg = !0), _.each(classes, function(value, key) {
                    value ? this.$el.addClass(this.mediaSizeClassPrefix + key) : this.$el.removeClass(this.mediaSizeClassPrefix + key)
                }, this)
            },
            fixElContainment: function() {
                this.$body || (this.$body = $("body"));
                var containment = [20 - this.$el.width(), 0, this.$body.width() - 20, this.$body.height() - 30],
                    positions = this.$el.position(),
                    new_positions = {};
                positions.left < containment[0] && (new_positions.left = containment[0]), positions.top < 0 && (new_positions.top = 0), positions.left > containment[2] && (new_positions.left = containment[2]), positions.top > containment[3] && (new_positions.top = containment[3]), this.$el.css(new_positions), this.trigger("fixElContainment"), this.setSize()
            },
            initDraggable: function() {
                this.$el.draggable({
                    iframeFix: !0,
                    handle: ".vc_panel-heading",
                    start: this.fixElContainment,
                    stop: this.fixElContainment
                }), this.draggable = !0
            },
            setSize: function() {
                this.trigger("setSize")
            },
            setTabs: function() {
                this.$tabs.length && (this.$tabs.find(".vc_panel-tabs-control").removeClass("vc_active").eq(this.options.startTab).addClass("vc_active"), this.$tabs.find(".vc_panel-tab").removeClass("vc_active").eq(this.options.startTab).addClass("vc_active"), window.setTimeout(this.setTabsSize, 100))
            },
            setTabsSize: function() {
                this.$tabs && this.$tabs.parents(".vc_with-tabs.vc_panel-body").css("margin-top", this.$tabs.find(".vc_panel-tabs-menu").outerHeight())
            },
            changeTab: function(e) {
                e && e.preventDefault && e.preventDefault(), e.target && this.$tabs && (e = $(e.target), this.$tabs.find(".vc_active").removeClass("vc_active"), e.parent().addClass("vc_active"), this.$el.find(e.data("target")).addClass("vc_active"), window.setTimeout(this.setTabsSize, 100))
            },
            showMessage: function(text, type) {
                if (this.showMessageDisabled) return !1;
                this.message_box_timeout && (this.$el.find(".vc_panel-message").remove(), window.clearTimeout(this.message_box_timeout)), this.message_box_timeout = !1;
                var $message_box = $('<div class="vc_panel-message type-' + type + '"></div>').appendTo(this.$el.find(".vc_ui-panel-content-container"));
                $message_box.text(text).fadeIn(), this.message_box_timeout = window.setTimeout(function() {
                    $message_box.remove()
                }, 6e3)
            },
            isVisible: function() {
                return this.$el.is(":visible")
            },
            resetMinimize: function() {
                this.$el.removeClass("vc_panel-opacity")
            }
        }), vc.PostSettingsPanelView = vc.PanelView.extend({
            events: {
                "click [data-save=true]": "save",
                "click [data-dismiss=panel]": "hide",
                "click [data-transparent=panel]": "toggleOpacity",
                "mouseover [data-transparent=panel]": "addOpacity",
                "mouseout [data-transparent=panel]": "removeOpacity"
            },
            saved_css_data: "",
            saved_js_header_data: "",
            saved_js_footer_data: "",
            saved_title: "",
            $title: !1,
            editor_css: !1,
            editor_js_header: !1,
            editor_js_footer: !1,
            post_settings_editor: !1,
            initialize: function() {
                vc.$custom_css = $("#vc_post-custom-css"), vc.$custom_js_header = $("#vc_post-custom-js-header"), vc.$custom_js_footer = $("#vc_post-custom-js-footer"), this.saved_css_data = vc.$custom_css.val(), this.saved_js_header_data = vc.$custom_js_header.val(), this.saved_js_footer_data = vc.$custom_js_footer.val(), this.saved_title = vc.title, this.initEditor(), this.$body = $("body"), _.bindAll(this, "setSize", "fixElContainment"), this.on("show", this.setSize, this), this.on("setSize", this.setResize, this), this.on("render", this.resetMinimize, this)
            },
            initEditor: function() {
                this.editor_css = new Vc_postSettingsEditor, this.editor_css.sel = "wpb_css_editor", this.editor_css.mode = "css", this.editor_css.is_focused = !0, this.editor_js_header = new Vc_postSettingsEditor, this.editor_js_header.sel = "wpb_js_header_editor", this.editor_js_header.mode = "javascript", this.editor_js_footer = new Vc_postSettingsEditor, this.editor_js_footer.sel = "wpb_js_footer_editor", this.editor_js_footer.mode = "javascript"
            },
            render: function() {
                return this.trigger("render"), this.$title = this.$el.find("#vc_page-title-field"), this.$title.val(vc.title), this.setEditor(), this.trigger("afterRender"), this
            },
            setEditor: function() {
                this.editor_css.setEditor(vc.$custom_css.val()), this.editor_js_header.setEditor(vc.$custom_js_header.val()), this.editor_js_footer.setEditor(vc.$custom_js_footer.val())
            },
            setSize: function() {
                this.editor_css.setSize(), this.editor_js_header.setSize(), this.editor_js_footer.setSize(), this.trigger("setSize")
            },
            save: function() {
                var title;
                this.$title && (title = this.$title.val()) !== vc.title && vc.frame.setTitle(title), this.setAlertOnDataChange(), vc.$custom_css.val(this.editor_css.getValue()), vc.$custom_js_header.val(this.editor_js_header.getValue()), vc.$custom_js_footer.val(this.editor_js_footer.getValue()), vc.frame_window && (vc.frame_window.vc_iframe.loadCustomCss(vc.$custom_css.val()), vc.frame_window.vc_iframe.loadCustomJsHeader(vc.$custom_js_header.val()), vc.frame_window.vc_iframe.loadCustomJsFooter(vc.$custom_js_footer.val())), vc.updateSettingsBadge(), this.showMessage(window.i18nLocale.page_settings_updated, "success"), this.trigger("save")
            },
            setAlertOnDataChange: function() {
                0 <= [this.saved_css_data !== this.editor_css.getValue(), this.saved_js_header_data !== this.editor_js_header.getValue(), this.saved_js_footer_data !== this.editor_js_footer.getValue(), this.$title && this.saved_title !== this.$title.val()].indexOf(!0) && vc.setDataChanged()
            }
        }), vc.PostSettingsSeoUIPanelView = vc.PanelView.extend({
            save: function() {
                for (var seo_data = $("#vc_setting-seo-form").serializeArray(), customFormat = {}, i = 0; i < seo_data.length; i++) customFormat[seo_data[i].name] = seo_data[i].value;
                $("#vc_post-custom-seo-settings").val(JSON.stringify(customFormat)), this.trigger("save"), this.hide()
            }
        }), vc.PostSettingsPanelViewBackendEditor = vc.PostSettingsPanelView.extend({
            render: function() {
                return this.trigger("render"), this.setEditor(), this.trigger("afterRender"), this
            },
            setAlertOnDataChange: function() {
                vc.saved_custom_css !== this.editor_css.getValue() && window.tinymce && (window.switchEditors.go("content", "tmce"), window.setTimeout(function() {
                    window.tinymce.get("content").isNotDirty = !1
                }, 1e3))
            },
            save: function() {
                vc.PostSettingsPanelViewBackendEditor.__super__.save.call(this), vc.storage.isChanged = !0, this.hide()
            }
        }), vc.TemplatesEditorPanelView = vc.PanelView.extend({
            events: {
                "click [data-dismiss=panel]": "hide",
                "click [data-transparent=panel]": "toggleOpacity",
                "mouseover [data-transparent=panel]": "addOpacity",
                "mouseout [data-transparent=panel]": "removeOpacity",
                "click .wpb_remove_template": "removeTemplate",
                "click [data-template_id]": "loadTemplate",
                "click [data-template_name]": "loadDefaultTemplate",
                "click #vc_template-save": "saveTemplate"
            },
            render: function() {
                this.trigger("render"), this.$name = $("#vc_template-name"), this.$list = $("#vc_template-list");
                var $tabs = $("#vc_tabs-templates");
                return $tabs.find(".vc_edit-form-tab-control").removeClass("vc_active").eq(0).addClass("vc_active"), $tabs.find('[data-vc-ui-element="panel-edit-element-tab"]').removeClass("vc_active").eq(0).addClass("vc_active"), $tabs.find(".vc_edit-form-link").on("click", function(e) {
                    e.preventDefault();
                    e = $(this);
                    $tabs.find(".vc_active").removeClass("vc_active"), e.parent().addClass("vc_active"), $(e.attr("href")).addClass("vc_active")
                }), this.trigger("afterRender"), this
            },
            removeTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                var e = $(e.currentTarget),
                    template_name = e.closest('[data-vc-ui-element="template-title"]').text();
                confirm(window.i18nLocale.confirm_deleting_template.replace("{template_name}", template_name)) && (e.closest('[data-vc-ui-element="template"]').remove(), this.$list.html(window.i18nLocale.loading), $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: {
                        action: "wpb_delete_template",
                        template_id: e.attr("rel"),
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    },
                    context: this
                }).done(function(html) {
                    this.$list.html(html)
                }))
            },
            loadTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget);
                $.ajax({
                    type: "POST",
                    url: vc.frame_window.location.href,
                    data: {
                        action: "vc_frontend_template",
                        template_id: e.data("template_id"),
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    },
                    context: this
                }).done(function(html) {
                    var template, data;
                    _.each($(html), function(element) {
                        if ("vc_template-data" === element.id) try {
                            data = JSON.parse(element.innerHTML)
                        } catch (err) {
                            window.console && window.console.warn && window.console.warn("loadTemplate json error", err)
                        }
                        "vc_template-html" === element.id && (template = element.innerHTML)
                    }), template && data && vc.builder.buildFromTemplate(template, data), this.showMessage(window.i18nLocale.template_added, "success"), vc.closeActivePanel()
                })
            },
            ajaxData: function($button) {
                return {
                    action: "vc_frontend_default_template",
                    template_name: $button.data("template_name"),
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            loadDefaultTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget);
                $.ajax({
                    type: "POST",
                    url: vc.frame_window.location.href,
                    data: this.ajaxData(e),
                    context: this
                }).done(function(html) {
                    var template, data;
                    _.each($(html), function(element) {
                        if ("vc_template-data" === element.id) try {
                            data = JSON.parse(element.innerHTML)
                        } catch (err) {
                            window.console && window.console.warn && window.console.warn("loadDefaultTemplate json error", err)
                        }
                        "vc_template-html" === element.id && (template = element.innerHTML)
                    }), template && data && vc.builder.buildFromTemplate(template, data), this.showMessage(window.i18nLocale.template_added, "success")
                })
            },
            saveTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                var shortcodes, e = this.$name.val();
                if (_.isString(e) && e.length) {
                    if (!(shortcodes = this.getPostContent()).trim().length) return this.showMessage(window.i18nLocale.template_is_empty, "error"), !1;
                    shortcodes = {
                        action: "wpb_save_template",
                        template: shortcodes,
                        template_name: e,
                        frontend: !0,
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    }, this.$name.val(""), this.showMessage(window.i18nLocale.template_save, "success"), this.reloadTemplateList(shortcodes)
                } else this.showMessage(window.i18nLocale.please_enter_templates_name, "error")
            },
            reloadTemplateList: function(data) {
                this.$list.html(window.i18nLocale.loading).load(window.ajaxurl, data)
            },
            getPostContent: function() {
                return vc.builder.getContent()
            }
        }), vc.TemplatesEditorPanelViewBackendEditor = vc.TemplatesEditorPanelView.extend({
            ajaxData: function($button) {
                return {
                    action: "vc_backend_template",
                    template_id: $button.attr("data-template_id"),
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            loadTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget);
                $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: this.ajaxData(e),
                    context: this
                }).done(function(shortcodes) {
                    _.each(vc.filters.templates, function(callback) {
                        shortcodes = callback(shortcodes)
                    }), vc.storage.append(shortcodes), vc.shortcodes.fetch({
                        reset: !0
                    }), vc.closeActivePanel()
                })
            },
            loadDefaultTemplate: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget);
                $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: {
                        action: "vc_backend_default_template",
                        template_name: e.attr("data-template_name"),
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    },
                    context: this
                }).done(function(shortcodes) {
                    _.each(vc.filters.templates, function(callback) {
                        shortcodes = callback(shortcodes)
                    }), vc.storage.append(shortcodes), vc.shortcodes.fetch({
                        reset: !0
                    })
                })
            },
            getPostContent: function() {
                return vc.storage.getContent()
            }
        }), vc.TemplatesPanelViewBackend = vc.PanelView.extend({
            $name: !1,
            $list: !1,
            template_load_action: "vc_backend_load_template",
            templateLoadPreviewAction: "vc_load_template_preview",
            save_template_action: "vc_save_template",
            delete_template_action: "vc_delete_template",
            appendedTemplateType: "my_templates",
            appendedTemplateCategory: "my_templates",
            appendedCategory: "my_templates",
            appendedClass: "my_templates",
            loadUrl: window.ajaxurl,
            events: $.extend(vc.PanelView.prototype.events, {
                "click .vc_template-save-btn": "saveTemplate",
                "click [data-template_id] [data-template-handler]": "loadTemplate",
                "click .vc_template-delete-icon": "removeTemplate"
            }),
            initialize: function() {
                _.bindAll(this, "checkInput", "saveTemplate"), vc.TemplatesPanelViewBackend.__super__.initialize.call(this)
            },
            render: function() {
                return this.$el.css("left", ($(window).width() - this.$el.width()) / 2), this.$name = this.$el.find('[data-js-element="vc-templates-input"]'), this.$name.off("keypress").on("keypress", this.checkInput), this.$list = this.$el.find(".vc_templates-list-my_templates"), vc.TemplatesPanelViewBackend.__super__.render.call(this)
            },
            saveTemplate: function(e) {
                var shortcodes, _this;
                return e && e.preventDefault && e.preventDefault(), e = this.$name.val(), _this = this, _.isString(e) && e.length ? (shortcodes = this.getPostContent()).trim().length ? (shortcodes = {
                    action: this.save_template_action,
                    template: shortcodes,
                    template_name: e,
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }, void this.setButtonMessage(void 0, void 0, !0).reloadTemplateList(shortcodes, function() {
                    _this.$name.val("").trigger("change")
                }, function() {
                    _this.showMessage(window.i18nLocale.template_save_error, "error"), _this.clearButtonMessage()
                })) : (this.showMessage(window.i18nLocale.template_is_empty, "error"), !1) : (this.showMessage(window.i18nLocale.please_enter_templates_name, "error"), !1)
            },
            checkInput: function(e) {
                if (13 === e.which) return this.saveTemplate(), !1
            },
            removeTemplate: function(e) {
                e && e.preventDefault && e.preventDefault(), e && e.stopPropagation && e.stopPropagation();
                var template_id, template_type, e = $(e.target).closest("[data-template_id]"),
                    template_name = e.find('[data-vc-ui-element="template-title"]').text();
                confirm(window.i18nLocale.confirm_deleting_template.replace("{template_name}", template_name)) && (template_id = e.data("template_id"), template_type = e.data("template_type"), template_name = e.data("template_action"), e.remove(), $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: {
                        action: template_name || this.delete_template_action,
                        template_id: template_id,
                        template_type: template_type,
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    },
                    context: this
                }).done(function() {
                    this.showMessage(window.i18nLocale.template_removed, "success"), vc.events.trigger("templates:delete", {
                        id: template_id,
                        type: template_type
                    })
                }))
            },
            reloadTemplateList: function(data, successCallback, errorCallback) {
                var _this = this;
                $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: data,
                    context: this
                }).done(function(html) {
                    _this.filter = !1, _this.$list || (_this.$list = _this.$el.find(".vc_templates-list-my_templates")), _this.$list.prepend($(html)), "function" == typeof successCallback && successCallback(html)
                }).fail("function" == typeof errorCallback ? errorCallback : function() {})
            },
            getPostContent: function() {
                return vc.shortcodes.stringify("template")
            },
            loadTemplate: function(e) {
                e && e.preventDefault && e.preventDefault(), e && e.stopPropagation && e.stopPropagation();
                e = $(e.target).closest("[data-template_id][data-template_type]");
                $.ajax({
                    type: "POST",
                    url: this.loadUrl,
                    data: {
                        action: this.template_load_action,
                        template_unique_id: e.data("template_id"),
                        template_type: e.data("template_type"),
                        vc_inline: !0,
                        _vcnonce: window.vcAdminNonce
                    },
                    context: this
                }).done(this.renderTemplate)
            },
            renderTemplate: function(html) {
                var models;
                _.each(vc.filters.templates, function(callback) {
                    html = callback(html)
                }), models = vc.storage.parseContent({}, html), _.each(models, function(model) {
                    vc.shortcodes.create(model)
                }), vc.closeActivePanel()
            },
            buildTemplatePreview: function(e) {
                e && e.preventDefault && e.preventDefault();
                try {
                    var url, $el = $(e.currentTarget),
                        $wrapper = $el.closest("[data-template_id]");
                    if ($wrapper.hasClass("vc_active") || $wrapper.hasClass("vc_loading")) $el.vcAccordion("collapseTemplate");
                    else {
                        var $localContent = $wrapper.find("[data-js-content]"),
                            localContentChilds = 0 < $localContent.children().length;
                        if (this.$content = $localContent, this.$content.find("iframe").length) return $el.vcAccordion("collapseTemplate"), !0;
                        var _this = this;
                        $el.vcAccordion("collapseTemplate", function() {
                            var question, templateId = $wrapper.data("template_id"),
                                templateType = $wrapper.data("template_type");
                            templateId && !localContentChilds && (question = "?", -1 < window.ajaxurl.indexOf("?") && (question = "&"), url = window.ajaxurl + question + $.param({
                                action: _this.templateLoadPreviewAction,
                                template_unique_id: templateId,
                                template_type: templateType,
                                vc_inline: !0,
                                post_id: vc_post_id,
                                _vcnonce: window.vcAdminNonce
                            }), $el.find("i").addClass("vc_ui-wp-spinner"), _this.$content.html('<iframe style="width: 100%;" data-vc-template-preview-frame="' + templateId + '"></iframe>'), (question = _this.$content.find("[data-vc-template-preview-frame]")).attr("src", url), $wrapper.addClass("vc_loading"), question.on("load", function() {
                                $wrapper.removeClass("vc_loading"), $el.find("i").removeClass("vc_ui-wp-spinner")
                            }))
                        })
                    }
                } catch (err) {
                    window.console && window.console.warn && window.console.warn("buildTemplatePreview error", err), this.showMessage("Failed to build preview", "error")
                }
            },
            setTemplatePreviewSize: function(height) {
                var iframe = this.$content.find("iframe");
                0 < iframe.length && (iframe = iframe[0], void 0 === height && (iframe.height = iframe.contentWindow.document.body.offsetHeight, height = iframe.contentWindow.document.body.scrollHeight), iframe.height = height + "px")
            }
        }), vc.TemplatesPanelViewFrontend = vc.TemplatesPanelViewBackend.extend({
            template_load_action: "vc_frontend_load_template",
            loadUrl: !1,
            initialize: function() {
                this.loadUrl = vc.$frame.attr("src"), vc.TemplatesPanelViewFrontend.__super__.initialize.call(this)
            },
            render: function() {
                return vc.TemplatesPanelViewFrontend.__super__.render.call(this)
            },
            renderTemplate: function(html) {
                var template, data;
                _.each($(html), function(element) {
                    if ("vc_template-data" === element.id) try {
                        data = JSON.parse(element.innerHTML)
                    } catch (err) {
                        window.console && window.console.warn && window.console.warn("renderTemplate error", err)
                    }
                    "vc_template-html" === element.id && (template = element.innerHTML)
                }), template && data && vc.builder.buildFromTemplate(template, data) ? this.showMessage(window.i18nLocale.template_added_with_id, "error") : this.showMessage(window.i18nLocale.template_added, "success"), vc.closeActivePanel()
            }
        }), vc.RowLayoutEditorPanelView = vc.PanelView.extend({
            events: {
                "click [data-dismiss=panel]": "hide",
                "click [data-transparent=panel]": "toggleOpacity",
                "mouseover [data-transparent=panel]": "addOpacity",
                "mouseout [data-transparent=panel]": "removeOpacity",
                "click .vc_layout-btn": "setLayout",
                "click #vc_row-layout-update": "updateFromInput"
            },
            _builder: !1,
            render: function(model) {
                return this.$input = $("#vc_row-layout"), model && (this.model = model), this.addCurrentLayout(), this.resetMinimize(), vc.column_trig_changes = !0, this
            },
            builder: function() {
                return this._builder || (this._builder = new vc.ShortcodesBuilder), this._builder
            },
            addCurrentLayout: function() {
                vc.shortcodes.sort();
                var string = _.map(vc.shortcodes.where({
                    parent_id: this.model.get("id")
                }), function(model) {
                    model = model.getParam("width");
                    return model || "1/1"
                }, "", this).join(" + ");
                this.$input.val(string)
            },
            isBuildComplete: function() {
                return this.builder().isBuildComplete()
            },
            setLayout: function(e) {
                if (e && e.preventDefault && e.preventDefault(), !this.isBuildComplete()) return !1;
                e = $(e.currentTarget).attr("data-cells"), e = this.model.view.convertRowColumns(e, this.builder());
                this.$input.val(e.join(" + "))
            },
            updateFromInput: function(e) {
                if (e && e.preventDefault && e.preventDefault(), !this.isBuildComplete()) return !1;
                var e = this.$input.val();
                !1 !== (e = this.validateCellsList(e)) ? this.model.view.convertRowColumns(e, this.builder()) : window.alert(window.i18nLocale.wrong_cells_layout)
            },
            validateCellsList: function(cells) {
                var b, num, denom, return_cells = [],
                    cells = cells.replace(/\s/g, "").split("+");
                return !(1e3 <= _.reduce(_.map(cells, function(c) {
                    var converted_c;
                    return c.match(/^[vc\_]{0,1}span\d{1,2}$/) ? !1 === (converted_c = vc_convert_column_span_size(c)) ? 1e3 : (b = converted_c.split(/\//), return_cells.push(b[0] + "" + b[1]), 12 * parseInt(b[0], 10) / parseInt(b[1], 10)) : !c.match(/^[1-9]|1[0-2]\/[1-9]|1[0-2]$/) || (b = c.split(/\//), num = parseInt(b[0], 10), 5 !== (denom = parseInt(b[1], 10)) && 0 != 12 % denom) || denom < num ? 1e3 : (return_cells.push(num + "" + denom), 5 === denom ? num : 12 * num / denom)
                }), function(num, memo) {
                    return memo += num
                }, 0)) && return_cells.join("_")
            }
        }), vc.RowLayoutEditorPanelViewBackend = vc.RowLayoutEditorPanelView.extend({
            builder: function() {
                return this.builder || (this.builder = vc.storage), this.builder
            },
            isBuildComplete: function() {
                return !0
            },
            setLayout: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget).attr("data-cells"), e = this.model.view.convertRowColumns(e);
                this.$input.val(e.join(" + "))
            }
        }), $(window).on("orientationchange", function() {
            vc.active_panel && vc.active_panel.$el.css({
                top: "",
                left: "auto",
                height: "auto",
                width: "auto"
            })
        }), $(window).on("resize.fixElContainment", function() {
            vc.active_panel && vc.active_panel.fixElContainment && vc.active_panel.fixElContainment()
        }), $("body").on("keyup change input", "[data-vc-disable-empty]", function() {
            var _this = $(this),
                $target = $(_this.data("vcDisableEmpty"));
            _this.val().length ? $target.prop("disabled", !1) : $target.prop("disabled", !0)
        })
    }(window.jQuery),
    function($) {
        "use strict";
        var Plugin, old, TabsLine = function(element, options) {
            var _this = this;
            this.options = options, this.$element = $(element), this.$dropdownContainer = this.$element.find(this.options.dropdownContainerSelector), this.$dropdown = this.$dropdownContainer.find(this.options.dropdownSelector), this.options.delayInit ? (_this.$element.addClass(this.options.initializingClass), setTimeout(function() {
                _this.options.autoRefresh || _this.refresh(), _this.moveTabs(), _this.$element.removeClass(_this.options.initializingClass)
            }, _this.options.delayInitTime)) : (this.options.autoRefresh || this.refresh(), this.moveTabs()), $(window).on("resize", $.proxy(this.moveTabs, this)), this.$dropdownContainer.on("click.vc.tabsLine", $.proxy(this.checkDropdownContainerActive, this))
        };
        TabsLine.DEFAULTS = {
            initializingClass: "vc_initializing",
            delayInit: !1,
            delayInitTime: 1e3,
            activeClass: "vc_active",
            visibleClass: "vc_visible",
            dropdownContainerSelector: '[data-vc-ui-element="panel-tabs-line-toggle"]',
            dropdownSelector: '[data-vc-ui-element="panel-tabs-line-dropdown"]',
            tabSelector: '>li:not([data-vc-ui-element="panel-tabs-line-toggle"])',
            dropdownTabSelector: "li",
            freeSpaceOffset: 5,
            autoRefresh: !1,
            showDevInfo: !1
        }, TabsLine.prototype.refresh = function() {
            var addClick, _this = this;
            return _this.tabs = [], _this.dropdownTabs = [], _this.$element.find(_this.options.tabSelector).each(function() {
                _this.tabs.push({
                    $tab: $(this),
                    width: $(this).outerWidth()
                })
            }), _this.$dropdown.find(_this.options.dropdownTabSelector).each(function() {
                var $tempElement = $(this).clone().css({
                    visibility: "hidden",
                    position: "fixed"
                });
                $tempElement.appendTo(_this.$element), _this.dropdownTabs.push({
                    $tab: $(this),
                    width: $tempElement.outerWidth()
                }), $tempElement.remove(), $(this).on("click", _this.options.onTabClick)
            }), "function" == typeof this.options.onTabClick && (_this.tabs.map(addClick = function(el) {
                void 0 === el.$tab.data("tabClickSet") && (el.$tab.on("click", $.proxy(_this.options.onTabClick, el.$tab)), el.$tab.data("tabClickSet", !0))
            }), _this.dropdownTabs.map(addClick)), this
        }, TabsLine.prototype.moveLastToDropdown = function() {
            var $element;
            return this.tabs.length && (($element = this.tabs.pop()).$tab.prependTo(this.$dropdown), this.dropdownTabs.unshift($element)), this.checkDropdownContainer(), this
        }, TabsLine.prototype.moveFirstToContainer = function() {
            var $element;
            return this.dropdownTabs.length && (($element = this.dropdownTabs.shift()).$tab.appendTo(this.$element), this.tabs.push($element)), this.checkDropdownContainer(), this
        }, TabsLine.prototype.getTabsWidth = function() {
            var tabsWidth = 0;
            return this.tabs.forEach(function(entry) {
                tabsWidth += entry.width
            }), tabsWidth
        }, TabsLine.prototype.isDropdownContainerVisible = function() {
            return this.$dropdownContainer.hasClass(this.options.visibleClass)
        }, TabsLine.prototype.getFreeSpace = function() {
            var freeSpace = this.$element.width() - this.getTabsWidth() - this.options.freeSpaceOffset;
            return this.isDropdownContainerVisible() && (freeSpace -= this.$dropdownContainer.outerWidth(), 1 === this.dropdownTabs.length) && 0 <= freeSpace - this.dropdownTabs[0].width + this.$dropdownContainer.outerWidth() && (freeSpace += this.$dropdownContainer.outerWidth()), freeSpace
        }, TabsLine.prototype.moveTabsToDropdown = function() {
            for (var i = this.tabs.length - 1; 0 <= i; i--) {
                if (!(this.getFreeSpace() < 0)) return this;
                this.moveLastToDropdown()
            }
            return this
        }, TabsLine.prototype.moveDropdownToTabs = function() {
            for (var dropdownTabsCount = this.dropdownTabs.length, i = 0; i < dropdownTabsCount; i++) {
                if (!(0 <= this.getFreeSpace() - this.dropdownTabs[0].width)) return this;
                this.moveFirstToContainer()
            }
            return this
        }, TabsLine.prototype.showDropdownContainer = function() {
            return this.$dropdownContainer.addClass(this.options.visibleClass), this
        }, TabsLine.prototype.hideDropdownContainer = function() {
            return this.$dropdownContainer.removeClass(this.options.visibleClass), this
        }, TabsLine.prototype.activateDropdownContainer = function() {
            return this.$dropdownContainer.addClass(this.options.activeClass), this
        }, TabsLine.prototype.deactivateDropdownContainer = function() {
            return this.$dropdownContainer.removeClass(this.options.activeClass), this
        }, TabsLine.prototype.checkDropdownContainerActive = function() {
            return this.$dropdown.find("." + this.options.activeClass + ":first").length ? this.activateDropdownContainer() : this.deactivateDropdownContainer(), this
        }, TabsLine.prototype.checkDropdownContainer = function() {
            return this.dropdownTabs.length ? this.showDropdownContainer() : this.hideDropdownContainer(), this.checkDropdownContainerActive(), this
        }, TabsLine.prototype.moveTabs = function() {
            return this.options.autoRefresh && this.refresh(), this.checkDropdownContainer(), this.moveTabsToDropdown(), this.moveDropdownToTabs(), this.options.showDevInfo && this.showDevInfo(), this
        }, TabsLine.prototype.showDevInfo = function() {
            var $devInfoBlock = $("#vc-ui-tabs-line-dev-info");
            $devInfoBlock.length && (this.$devBlock = $devInfoBlock), void 0 === this.$devBlock && (this.$devBlock = $('<div id="vc-ui-tabs-line-dev-info" />').css({
                position: "fixed",
                right: "40px",
                top: "40px",
                padding: "7px 12px",
                border: "1px solid rgba(0, 0, 0, 0.2)",
                background: "rgba(0, 0, 0, 0.7)",
                color: "#0a0",
                "border-radius": "5px",
                "font-family": "tahoma",
                "font-size": "12px",
                "z-index": 1100
            }), this.$devBlock.appendTo("body")), void 0 === this.$devInfo && (this.$devInfo = $("<div />").css({
                "margin-bottom": "7px",
                "padding-bottom": "7px",
                "border-bottom": "1px dashed rgba(0, 200, 0, .35)"
            }), this.$devInfo.appendTo(this.$devBlock)), this.$devInfo.empty(), this.$devInfo.append($("<div />").text("Tabs count: " + this.tabs.length)), this.$devInfo.append($("<div />").text("Dropdown count: " + this.dropdownTabs.length)), this.$devInfo.append($("<div />").text("El width: " + this.$element.width())), this.$devInfo.append($("<div />").text("Tabs width: " + this.getTabsWidth())), this.$devInfo.append($("<div />").text("Tabs width with dots: " + (this.getTabsWidth() + this.$dropdownContainer.outerWidth()))), this.$devInfo.append($("<div />").text("Free space: " + this.getFreeSpace())), this.tabs.length && this.$devInfo.append($("<div />").text("Last tab width: " + this.tabs[this.tabs.length - 1].width)), this.dropdownTabs.length && this.$devInfo.append($("<div />").text("First dropdown tab width: " + this.dropdownTabs[0].width))
        }, old = $.fn.vcTabsLine, $.fn.vcTabsLine = Plugin = function(option) {
            return this.each(function() {
                var $this = $(this),
                    optionsData = $this.data("vcUiTabsLine"),
                    data = $this.data("vc.tabsLine"),
                    optionsData = $.extend(!0, {}, TabsLine.DEFAULTS, $this.data(), optionsData, "object" == typeof option && option),
                    action = "string" == typeof option ? option : optionsData.action;
                data || $this.data("vc.tabsLine", data = new TabsLine(this, optionsData)), action && data[action]()
            })
        }, $.fn.vcTabsLine.Constructor = TabsLine, $.fn.vcTabsLine.noConflict = function() {
            return $.fn.vcTabsLine = old, this
        }, $(window).on("load", function() {
            $("[data-vc-ui-tabs-line]").each(function() {
                var $vcTabsLine = $(this);
                Plugin.call($vcTabsLine, $vcTabsLine.data())
            })
        })
    }(window.jQuery),
    function() {
        "use strict";
        window.vc.HelperAjax = {
            ajax: !1,
            checkAjax: function() {
                this.ajax && this.ajax.abort()
            },
            resetAjax: function() {
                this.ajax = !1
            }
        }
    }(),
    function() {
        "use strict";
        window.vc.HelperPrompts = {
            uiEvents: {
                render: "removeAllPrompts"
            },
            removeAllPrompts: function() {
                this.$el.find(".vc_ui-panel-content-container").removeClass("vc_ui-content-hidden"), this.$el.find(".vc_ui-prompt").remove()
            }
        }
    }(),
    function() {
        "use strict";
        window.vc.HelperPanelViewDraggable = {
            draggable: !0,
            draggableOptions: {
                iframeFix: !0,
                handle: '[data-vc-ui-element="panel-heading"]'
            },
            uiEvents: {
                show: "initDraggable"
            },
            initDraggable: function() {
                this.$el.draggable(_.extend({}, this.draggableOptions, {
                    start: this.fixElContainment,
                    stop: this.fixElContainment
                }))
            }
        }
    }(),
    function($) {
        "use strict";
        window.vc.HelperPanelViewResizable = {
            sizeInitialized: !1,
            uiEvents: {
                show: "setSavedSize initResize",
                tabChange: "setDefaultHeightSettings",
                afterMinimize: "setupOnMinimize",
                afterUnminimize: "initResize",
                fixElContainment: "saveUIPanelSizes"
            },
            setDefaultHeightSettings: function() {
                this.$el.css("height", "auto"), this.$el.css("maxHeight", "75vh")
            },
            initResize: function() {
                var _this = this;
                this.$el.data("uiResizable") && this.$el.resizable("destroy"), this.$el.resizable({
                    minHeight: 240,
                    minWidth: 380,
                    resize: function() {
                        _this.trigger("resize")
                    },
                    handles: "n, e, s, w, ne, se, sw, nw",
                    start: function(e, ui) {
                        _this.trigger("beforeResizeStart"), _this.$el.css("maxHeight", "none"), _this.$el.css("height", ui.size.height), $("iframe").css("pointerEvents", "none"), _this.trigger("afterResizeStart")
                    },
                    stop: function() {
                        _this.trigger("beforeResizeStop"), $("iframe").css("pointerEvents", ""), _this.saveUIPanelSizes(), _this.trigger("afterResizeStop")
                    }
                }), this.content().addClass("vc_properties-list-init"), this.trigger("resize")
            },
            setSavedSize: function() {
                if (this.setDefaultHeightSettings(), vc.is_mobile) return !1;
                var sizes = {
                    width: getUserSetting(this.panelName + "_vcUIPanelWidth"),
                    left: getUserSetting(this.panelName + "_vcUIPanelLeft").replace("minus", "-"),
                    top: getUserSetting(this.panelName + "_vcUIPanelTop").replace("minus", "-")
                };
                _.isEmpty(sizes.width) || this.$el.width(sizes.width), _.isEmpty(sizes.left) || this.$el.css("left", sizes.left), _.isEmpty(sizes.top) || this.$el.css("top", sizes.top), this.sizeInitialized = !0
            },
            saveUIPanelSizes: function() {
                if (!1 === this.sizeInitialized) return !1;
                var sizes = {
                    width: this.$el.width(),
                    left: parseInt(this.$el.css("left"), 10),
                    top: parseInt(this.$el.css("top"), 10)
                };
                setUserSetting(this.panelName + "_vcUIPanelWidth", sizes.width), setUserSetting(this.panelName + "_vcUIPanelLeft", sizes.left.toString().replace("-", "minus") + "px"), setUserSetting(this.panelName + "_vcUIPanelTop", sizes.top.toString().replace("-", "minus") + "px")
            },
            setupOnMinimize: function() {
                this.$el.data("uiResizable") && this.$el.resizable("destroy"), this.$el.resizable({
                    minWidth: 380,
                    handles: "w, e",
                    start: function(e) {
                        $("iframe").css("pointerEvents", "none")
                    },
                    stop: function() {
                        $("iframe").css("pointerEvents", "")
                    }
                })
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.HelperTemplatesPanelViewSearch = {
            searchSelector: "[data-vc-templates-name-filter]",
            events: {
                "keyup [data-vc-templates-name-filter]": "searchTemplate",
                "search [data-vc-templates-name-filter]": "searchTemplate"
            },
            uiEvents: {
                show: "focusToSearch"
            },
            focusToSearch: function() {
                vc.is_mobile || $(this.searchSelector, this.$el).trigger("focus")
            },
            searchTemplate: function(e) {
                e = $(e.currentTarget);
                e.val().length ? this.searchByName(e.val()) : this.clearSearch()
            },
            clearSearch: function() {
                this.$el.find("[data-vc-templates-name-filter]").val(""), this.$el.find("[data-template_name]").css("display", "block"), this.$el.removeAttr("data-vc-template-search"), this.$el.find(".vc-search-result-empty").removeClass("vc-search-result-empty");
                var ev = new jQuery.Event("click");
                ev.isClearSearch = !0, this.$el.find('.vc_panel-tabs-control:first [data-vc-ui-element="panel-tab-control"]').trigger(ev)
            },
            searchByName: function(name) {
                this.$el.find(".vc_panel-tabs-control.vc_active").removeClass("vc_active"), this.$el.attr("data-vc-template-search", "true"), this.$el.find("[data-template_name]").css("display", "none"), this.$el.find('[data-template_name*="' + vc_slugify(name) + '"]').css("display", "block"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"]').each(function() {
                    var $el = $(this);
                    $el.removeClass("vc-search-result-empty"), $el.find("[data-template_name]:visible").length || $el.addClass("vc-search-result-empty")
                })
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.HelperPanelViewHeaderFooter = {
            buttonMessageTimeout: !1,
            events: {
                'click [data-vc-ui-element="button-save"]': "save",
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="button-minimize"]': "toggleOpacity"
            },
            uiEvents: {
                save: "setButtonMessage",
                render: "clearButtonMessage"
            },
            resetMinimize: function() {
                this.$el.removeClass("vc_panel-opacity"), this.$el.removeClass("vc_minimized")
            },
            toggleOpacity: function(e) {
                e.preventDefault();
                var hasStyle, _this = this,
                    $target = this.$el,
                    $panel = $target.find($target.data("vcPanel")),
                    $panelContainer = $panel.closest($panel.data("vcPanelContainer")),
                    $trigger = $(e.currentTarget);
                void 0 === $target.data("vcHasHeight") && $target.data("vcHasHeight", (e = $target.attr("style"), hasStyle = !1, e && e.split(";").forEach(function(e) {
                    e = e.split(":");
                    "height" === $.trim(e[0]) && (hasStyle = !0)
                }), hasStyle)), $target.hasClass("vc_minimized") ? (void 0 === $target.data("vcMinimizeHeight") && $target.data("vcMinimizeHeight", $(window).height() - .2 * $(window).height()), $target.animate({
                    height: $target.data("vcMinimizeHeight")
                }, {
                    duration: 400,
                    start: function() {
                        $trigger.prop("disabled", !0), $target.addClass("vc_animating"), _this.tabsMenu && _this.tabsMenu() && _this.tabsMenu().vcTabsLine("moveTabs")
                    },
                    complete: function() {
                        $target.removeClass("vc_minimized"), $target.removeClass("vc_animating"), $target.data("vcHasHeight") || $target.css({
                            height: ""
                        }), _this.trigger("afterUnminimize"), $trigger.prop("disabled", !1)
                    }
                })) : ($target.data("vcMinimizeHeight", $target.height()), $target.animate({
                    height: $panel.outerHeight() + $panelContainer.outerHeight() - $panelContainer.height()
                }, {
                    duration: 400,
                    start: function() {
                        $trigger.prop("disabled", !0), $target.addClass("vc_animating")
                    },
                    complete: function() {
                        $target.addClass("vc_minimized"), $target.removeClass("vc_animating"), $target.css({
                            height: ""
                        }), _this.trigger("afterMinimize"), $trigger.prop("disabled", !1)
                    }
                }))
            },
            setButtonMessage: function(message, type, showInBackend) {
                var currentTextHtml;
                return void 0 === showInBackend && (showInBackend = !1), this.clearButtonMessage = _.bind(this.clearButtonMessage, this), !showInBackend && !vc.frame_window || this.buttonMessageTimeout || (void 0 === message && (message = window.i18nLocale.ui_saved), void 0 === type && (type = "success"), currentTextHtml = (showInBackend = this.$el.find('[data-vc-ui-element="button-save"]')).html(), showInBackend.addClass("vc_ui-button-" + type + " vc_ui-button-undisabled").removeClass("vc_ui-button-action").data("vcCurrentTextHtml", currentTextHtml).data("vcCurrentTextType", type).html(message), _.delay(this.clearButtonMessage, 5e3), this.buttonMessageTimeout = !0), this
            },
            clearButtonMessage: function() {
                var type, currentTextHtml, $saveBtn;
                this.buttonMessageTimeout && (window.clearTimeout(this.buttonMessageTimeout), currentTextHtml = ($saveBtn = this.$el.find('[data-vc-ui-element="button-save"]')).data("vcCurrentTextHtml") || "Save", type = $saveBtn.data("vcCurrentTextType"), $saveBtn.html(currentTextHtml).removeClass("vc_ui-button-" + type + " vc_ui-button-undisabled").addClass("vc_ui-button-action"), this.buttonMessageTimeout = !1)
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.HelperPanelSettingsPostCustomLayout = {
            events: {
                'click [data-vc-ui-element="button-save"]': "save",
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
                "click .vc_post-custom-layout": "changePostCustomLayout"
            },
            changePostCustomLayout: function(e) {
                var layout_name;
                e && e.preventDefault && (e.preventDefault(), layout_name = (e = $(e.currentTarget)).attr("data-post-custom-layout"), e.addClass("vc-active-post-custom-layout"), e.siblings().removeClass("vc-active-post-custom-layout"), $("input[name=vc_post_custom_layout]").val(layout_name))
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.TemplateWindowUIPanelBackendEditor = vc.TemplatesPanelViewBackend.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperTemplatesPanelViewSearch).extend({
            panelName: "template_window",
            showMessageDisabled: !1,
            initialize: function() {
                window.vc.TemplateWindowUIPanelBackendEditor.__super__.initialize.call(this), this.trigger("show", this.initTemplatesTabs, this)
            },
            show: function() {
                this.clearSearch(), window.vc.TemplateWindowUIPanelBackendEditor.__super__.show.call(this)
            },
            initTemplatesTabs: function() {
                this.$el.find('[data-vc-ui-element="panel-tabs-controls"]').vcTabsLine("moveTabs")
            },
            showMessage: function(text, type) {
                if (this.showMessageDisabled) return !1;
                this.message_box_timeout && (this.$el.find("[data-vc-panel-message]").remove(), window.clearTimeout(this.message_box_timeout)), this.message_box_timeout = !1;
                var $messageBox, messageBoxTemplate = vc.template('<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>"><div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>');
                switch (type) {
                    case "error":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "danger",
                            icon: "times",
                            text: text
                        }));
                        break;
                    case "warning":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "warning",
                            icon: "exclamation-triangle",
                            text: text
                        }));
                        break;
                    case "success":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "success",
                            icon: "check",
                            text: text
                        }))
                }
                $messageBox.prependTo(this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active')), $messageBox.fadeIn(), this.message_box_timeout = window.setTimeout(function() {
                    $messageBox.remove()
                }, 6e3)
            },
            changeTab: function(e) {
                e && e.preventDefault && e.preventDefault(), e && !e.isClearSearch && this.clearSearch();
                e = $(e.currentTarget);
                e.parent().hasClass("vc_active") || (this.$el.find('[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])').removeClass("vc_active"), e.parent().addClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_active').removeClass("vc_active"), this.$el.find(e.data("vcUiElementTarget")).addClass("vc_active"), this.$tabsMenu && this.$tabsMenu.vcTabsLine("checkDropdownContainerActive"))
            },
            setPreviewFrameHeight: function(templateID, height) {
                parseInt(height, 10) < 100 && (height = 100), $('data-vc-template-preview-frame="' + templateID + '"').height(height)
            }
        }), window.vc.TemplateWindowUIPanelBackendEditor.prototype.events = $.extend(!0, window.vc.TemplateWindowUIPanelBackendEditor.prototype.events, {
            'click [data-vc-ui-element="button-save"]': "save",
            'click [data-vc-ui-element="button-close"]': "hide",
            'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
            "keyup [data-vc-templates-name-filter]": "searchTemplate",
            "search [data-vc-templates-name-filter]": "searchTemplate",
            "click .vc_template-save-btn": "saveTemplate",
            "click [data-template_id] [data-template-handler]": "loadTemplate",
            'click [data-vc-container=".vc_ui-list-bar"][data-vc-preview-handler]': "buildTemplatePreview",
            'click [data-vc-ui-delete="template-title"]': "removeTemplate",
            'click [data-vc-ui-element="panel-tab-control"]': "changeTab"
        }), window.vc.TemplateWindowUIPanelFrontendEditor = vc.TemplatesPanelViewFrontend.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperTemplatesPanelViewSearch).extend({
            panelName: "template_window",
            showMessageDisabled: !1,
            show: function() {
                this.clearSearch(), window.vc.TemplateWindowUIPanelFrontendEditor.__super__.show.call(this)
            },
            showMessage: function(text, type) {
                if (this.showMessageDisabled) return !1;
                this.message_box_timeout && (this.$el.find("[data-vc-panel-message]").remove(), window.clearTimeout(this.message_box_timeout)), this.message_box_timeout = !1;
                var $messageBox, messageBoxTemplate = vc.template('<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>"><div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>');
                switch (type) {
                    case "error":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "danger",
                            icon: "times",
                            text: text
                        }));
                        break;
                    case "warning":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "warning",
                            icon: "exclamation-triangle",
                            text: text
                        }));
                        break;
                    case "success":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "success",
                            icon: "check",
                            text: text
                        }))
                }
                $messageBox.prependTo(this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active')), $messageBox.fadeIn(), this.message_box_timeout = window.setTimeout(function() {
                    $messageBox.remove()
                }, 6e3)
            },
            changeTab: function(e) {
                e && e.preventDefault && e.preventDefault(), e && !e.isClearSearch && this.clearSearch();
                e = $(e.currentTarget);
                e.parent().hasClass("vc_active") || (this.$el.find('[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])').removeClass("vc_active"), e.parent().addClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_active').removeClass("vc_active"), this.$el.find(e.data("vcUiElementTarget")).addClass("vc_active"), this.$tabsMenu && this.$tabsMenu.vcTabsLine("checkDropdownContainerActive"))
            }
        }), $.fn.vcAccordion.Constructor.prototype.collapseTemplate = function(showCallback) {
            var $triggerPanel, $wrapper, $panel, $this = this.$element,
                i = 0,
                $triggers = this.getContainer().find("[data-vc-preview-handler]").each(function() {
                    var $this = $(this),
                        accordion = $this.data("vc.accordion");
                    void 0 === accordion && ($this.vcAccordion(), accordion = $this.data("vc.accordion")), accordion && accordion.setIndex && accordion.setIndex(i++)
                }).filter(function() {
                    var accordion = $(this).data("vc.accordion");
                    return accordion.getTarget().hasClass(accordion.activeClass)
                }).filter(function() {
                    return $this[0] !== this
                });
            $triggers.length && $.fn.vcAccordion.call($triggers, "hide"), this.isActive() ? $.fn.vcAccordion.call($this, "hide") : ($.fn.vcAccordion.call($this, "show"), $triggerPanel = $this.closest(".vc_ui-list-bar-item"), $wrapper = $this.closest("[data-template_id]"), $panel = $wrapper.closest("[data-vc-ui-element=panel-content]").parent(), setTimeout(function() {
                var posit;
                Math.round($wrapper.offset().top - $panel.offset().top) < 0 && (posit = Math.round($wrapper.offset().top - $panel.offset().top + $panel.scrollTop() - $triggerPanel.height()), $panel.animate({
                    scrollTop: posit
                }, 400)), "function" == typeof showCallback && showCallback($wrapper, $panel)
            }, 400))
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.element_start_index = 0, window.vc.AddElementUIPanelBackendEditor = vc.PanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).extend({
            el: "#vc_ui-panel-add-element",
            searchSelector: "#vc_elements_name_filter",
            prepend: !1,
            builder: "",
            events: {
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="panel-tab-control"]': "filterElements",
                "click .vc_shortcode-link": "createElement",
                "keyup #vc_elements_name_filter": "filterElements",
                "search #vc_elements_name_filter": "filterElements",
                "click [data-vc-manage-elements]": "openPresetWindow"
            },
            initialize: function() {
                window.vc.AddElementUIPanelBackendEditor.__super__.initialize.call(this), window.vc.events.on("shortcodes:add", this.addCustomCssStyleTag.bind(this)), window.vc.events.on("vc:savePreset", this.updateAddElementPopUp.bind(this)), window.vc.events.on("vc:deletePreset", this.removePresetFromAddElementPopUp.bind(this))
            },
            render: function(model, prepend) {
                return _.isUndefined(vc.ShortcodesBuilder) || (this.builder = new vc.ShortcodesBuilder), this.$el.is(":hidden") && window.vc.closeActivePanel(), (window.vc.active_panel = this).prepend = !!_.isBoolean(prepend) && prepend, this.place_after_id = !!_.isString(prepend) && prepend, this.model = !!_.isObject(model) && model, this.$content = this.$el.find('[data-vc-ui-element="panel-add-element-list"]'), this.$buttons = $('[data-vc-ui-element="add-element-button"]', this.$content), this.buildFiltering(), this.$el.find('[data-vc-ui-element="panel-tab-control"]').eq(0).click(), this.show(), this.$el.find('[data-vc-ui-element="panel-tabs-controls"]').vcTabsLine("moveTabs"), vc.is_mobile || $(this.searchSelector).trigger("focus"), vc.AddElementUIPanelBackendEditor.__super__.render.call(this)
            },
            buildFiltering: function() {
                var tag, asParent, parentSelector, itemSelector = '[data-vc-ui-element="add-element-button"]',
                    notIn = this._getNotIn(this.model ? this.model.get("shortcode") : "");
                $(this.searchSelector).val(""), this.$content.addClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", "*"), asParent = !(!(tag = this.model ? this.model.get("shortcode") : "vc_column") || _.isUndefined(vc.getMapped(tag).as_parent)) && vc.getMapped(tag).as_parent, _.isObject(asParent) ? (parentSelector = [], _.isString(asParent.only) && parentSelector.push(_.reduce(asParent.only.replace(/\s/, "").split(","), function(memo, val) {
                    return memo + (_.isEmpty(memo) ? "" : ",") + '[data-element="' + val.trim() + '"]'
                }, "")), _.isString(asParent.except) && parentSelector.push(_.reduce(asParent.except.replace(/\s/, "").split(","), function(memo, val) {
                    return memo + ':not([data-element="' + val.trim() + '"])'
                }, "")), itemSelector += parentSelector.join(",")) : notIn && (itemSelector = notIn), !1 === tag || _.isUndefined(vc.getMapped(tag).allowed_container_element) || (!1 === vc.getMapped(tag).allowed_container_element ? itemSelector += ":not([data-is-container=true])" : _.isString(vc.getMapped(tag).allowed_container_element) && (itemSelector += ":not([data-is-container=true]), [data-element=" + vc.getMapped(tag).allowed_container_element + "]")), this.$buttons.removeClass("vc_visible").addClass("vc_inappropriate"), $(itemSelector, this.$content).removeClass("vc_inappropriate").addClass("vc_visible"), this.hideEmptyFilters()
            },
            hideEmptyFilters: function() {
                var _this = this;
                this.$el.find('[data-vc-ui-element="panel-add-element-tab"].vc_active').removeClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-add-element-tab"]:first').addClass("vc_active"), this.$el.find("[data-filter]").each(function() {
                    $($(this).data("filter") + ".vc_visible:not(.vc_inappropriate)", _this.$content).length ? $(this).parent().show() : $(this).parent().hide()
                })
            },
            _getNotIn: _.memoize(function(tag) {
                return '[data-vc-ui-element="add-element-button"]:not(' + _.reduce(vc.map, function(memo, shortcode) {
                    var separator = _.isEmpty(memo) ? "" : ",";
                    return _.isObject(shortcode.as_child) ? (_.isString(shortcode.as_child.only) && !_.contains(shortcode.as_child.only.replace(/\s/, "").split(","), tag) && (memo += separator + "[data-element=" + shortcode.base + "]"), _.isString(shortcode.as_child.except) && _.contains(shortcode.as_child.except.replace(/\s/, "").split(","), tag) && (memo += separator + "[data-element=" + shortcode.base + "]")) : !1 === shortcode.as_child && (memo += separator + "[data-element=" + shortcode.base + "]"), memo
                }, "") + ")"
            }),
            filterElements: function(e) {
                var $control, filter, nameFilter;
                e ? (e.preventDefault && e.preventDefault(), e.stopPropagation && e.stopPropagation()) : e = window.event, $control = $(e.currentTarget), filter = '[data-vc-ui-element="add-element-button"]', nameFilter = $(this.searchSelector).val(), this.$content.removeClass("vc_filter-all"), $('[data-vc-ui-element="panel-add-element-tab"].vc_active').removeClass("vc_active"), $control.is("[data-filter]") ? ($control.parent().addClass("vc_active"), filter += $control = $control.data("filter"), "*" === $control ? this.$content.addClass("vc_filter-all") : this.$content.removeClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", $control.replace(".js-category-", "")), $(this.searchSelector).val("")) : nameFilter.length ? (filter += ":containsi('" + nameFilter + "'):not('.vc_element-deprecated')", this.$content.attr("data-vc-ui-filter", "name:" + nameFilter)) : nameFilter.length || ($('[data-vc-ui-element="panel-tab-control"][data-filter="*"]').parent().addClass("vc_active"), this.$content.attr("data-vc-ui-filter", "*").addClass("vc_filter-all")), $(".vc_visible", this.$content).removeClass("vc_visible"), $(filter, this.$content).addClass("vc_visible"), nameFilter.length && 13 === (e.keyCode || e.which) && 1 === ($control = $(".vc_visible:not(.vc_inappropriate)", this.$content)).length && $control.find("[data-vc-clickable]").click()
            },
            createElement: function(e) {
                var options, model, column, row, column_params, row_params, tag, preset, presetType;
                e && e.preventDefault && e.preventDefault(), tag = (e = $(e.currentTarget)).data("tag"), row_params = {}, column_params = {
                    width: "1/1"
                }, (e = e.closest("[data-preset]")) && (preset = e.data("preset"), presetType = e.data("element")), !1 === this.model ? (window.vc.storage.lock(), "vc_section" === tag ? (e = {
                    shortcode: tag
                }, preset && "vc_section" === presetType && (e.preset = preset), model = vc.shortcodes.create(e)) : (e = {
                    shortcode: "vc_row",
                    params: row_params
                }, preset && presetType === tag && (e.preset = preset), e = {
                    shortcode: "vc_column",
                    params: column_params,
                    parent_id: (row = vc.shortcodes.create(e)).id,
                    root_id: row.id
                }, preset && "vc_column" === presetType && (e.preset = preset), column = vc.shortcodes.create(e), model = row, "vc_row" !== tag && (options = {
                    shortcode: tag,
                    parent_id: column.id,
                    root_id: row.id
                }, preset && presetType === tag && (options.preset = preset), model = vc.shortcodes.create(options)))) : model = "vc_row" === tag ? (column = "vc_section" === this.model.get("shortcode") ? (window.vc.storage.lock(), row = vc.shortcodes.create({
                    shortcode: "vc_row",
                    params: row_params,
                    parent_id: this.model.id,
                    order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()
                }), vc.shortcodes.create({
                    shortcode: "vc_column",
                    params: column_params,
                    parent_id: row.id,
                    root_id: row.id
                })) : (e = {}, row_params = {
                    width: "1/1"
                }, window.vc.storage.lock(), row = vc.shortcodes.create({
                    shortcode: "vc_row_inner",
                    params: e,
                    parent_id: this.model.id,
                    order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()
                }), vc.shortcodes.create({
                    shortcode: "vc_column_inner",
                    params: row_params,
                    parent_id: row.id,
                    root_id: row.id
                })), row) : (options = {
                    shortcode: tag,
                    parent_id: this.model.id,
                    order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
                    root_id: this.model.get("root_id")
                }, preset && presetType === tag && (options.preset = preset), vc.shortcodes.create(options)), this.model = model, column_params = !(_.isBoolean(vc.getMapped(tag).show_settings_on_create) && !1 === vc.getMapped(tag).show_settings_on_create), this.model.get("shortcode"), this.hide(), column_params && this.showEditForm()
            },
            getFirstPositionIndex: function() {
                return --window.vc.element_start_index, vc.element_start_index
            },
            show: function() {
                this.$el.addClass("vc_active"), this.trigger("show")
            },
            hide: function() {
                this.$el.removeClass("vc_active"), window.vc.active_panel = !1, this.trigger("hide")
            },
            showEditForm: function() {
                window.vc.edit_element_block_view.render(this.model)
            },
            addCustomCssStyleTag: function(model) {
                model && model.getParam && (model = model.getParam("css")) && vc.frame_window && window.vc.frame_window.vc_iframe.setCustomShortcodeCss(model)
            },
            updateAddElementPopUp: function(id, shortcode, title, data) {
                var $newPreset = this.$el.find('[data-element="' + shortcode + '"]:first').clone(!0);
                vc_all_presets[id] = data, $newPreset.find("[data-vc-shortcode-name]").text(title), $newPreset.find(".vc_element-description").text(""), $newPreset.attr("data-preset", id), $newPreset.addClass("js-category-_my_elements_"), $newPreset.insertAfter(this.$el.find('[data-element="' + shortcode + '"]:last')), this.$el.find('[data-filter="js-category-_my_elements_"]').show();
                data = this.$body.find('[data-vc-ui-element="panel-preset"] [data-vc-presets-list-content] .vc_ui-template:first').clone(!0);
                data.find('[data-vc-ui-element="template-title"]').attr("title", title).text(title), data.find('[data-vc-ui-delete="preset-title"]').attr("data-preset", id).attr("data-preset-parent", shortcode), data.find("[data-vc-ui-add-preset]").attr("data-preset", id).attr("id", shortcode).attr("data-tag", shortcode), data.show(), data.insertAfter(this.$body.find('[data-vc-ui-element="panel-preset"] [data-vc-presets-list-content] .vc_ui-template:last'))
            },
            removePresetFromAddElementPopUp: function(id) {
                this.$el.find('[data-preset="' + id + '"]').remove()
            },
            openPresetWindow: function(e) {
                e && e.preventDefault && e.preventDefault(), window.vc.preset_panel_view.render().show()
            }
        }), window.vc.AddElementUIPanelFrontendEditor = vc.AddElementUIPanelBackendEditor.vcExtendUI(vc.HelperPanelViewHeaderFooter).extend({
            events: {
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="panel-tab-control"]': "filterElements",
                "click .vc_shortcode-link": "createElement",
                "keyup #vc_elements_name_filter": "filterElements"
            },
            createElement: function(e) {
                var options, i, column_params, row_params, tag, preset, presetType;
                for (e && e.preventDefault && e.preventDefault(), tag = (e = $(e.currentTarget)).data("tag"), row_params = {}, column_params = {
                        width: "1/1"
                    }, (e = e.closest("[data-preset]")) && (preset = e.data("preset"), presetType = e.data("element")), this.prepend && (window.vc.activity = "prepend"), 0 == this.model ? "vc_section" === tag ? (e = {
                        shortcode: tag
                    }, preset && "vc_section" === presetType && (e.preset = preset), this.builder.create(e)) : (e = {
                        shortcode: "vc_row",
                        params: row_params
                    }, preset && "vc_row" === presetType && (e.preset = preset), this.builder.create(e), e = {
                        shortcode: "vc_column",
                        parent_id: this.builder.lastID(),
                        params: column_params
                    }, preset && "vc_column" === presetType && (e.preset = preset), this.builder.create(e), "vc_row" !== tag && (options = {
                        shortcode: tag,
                        parent_id: this.builder.lastID()
                    }, preset && presetType === tag && (options.preset = preset), this.builder.create(options))) : "vc_row" === tag ? "vc_section" === this.model.get("shortcode") ? this.builder.create({
                        shortcode: "vc_row",
                        params: row_params,
                        parent_id: this.model.id,
                        order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
                    }).create({
                        shortcode: "vc_column",
                        params: column_params,
                        parent_id: this.builder.lastID()
                    }) : (e = {
                        width: "1/1"
                    }, this.builder.create({
                        shortcode: "vc_row_inner",
                        params: {},
                        parent_id: this.model.id,
                        order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
                    }).create({
                        shortcode: "vc_column_inner",
                        params: e,
                        parent_id: this.builder.lastID()
                    })) : (options = {
                        shortcode: tag,
                        parent_id: this.model.id,
                        order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
                    }, preset && presetType === tag && (options.preset = preset), this.builder.create(options)), this.model = this.builder.last(), i = this.builder.models.length - 1; 0 <= i; i--) this.builder.models[i].get("shortcode");
                _.isString(vc.getMapped(tag).default_content) && vc.getMapped(tag).default_content.length && (row_params = this.builder.parse({}, window.vc.getMapped(tag).default_content, this.builder.last().toJSON()), _.each(row_params, function(object) {
                    object.default_content = !0, this.builder.create(object)
                }, this)), this.model = this.builder.last(), column_params = !(_.isBoolean(vc.getMapped(tag).show_settings_on_create) && !1 === vc.getMapped(tag).show_settings_on_create), this.hide(), column_params && this.showEditForm(), this.builder.render()
            }
        })
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.ExtendPresets = {
            settingsMenuSelector: '[data-vc-ui-element="settings-dropdown-list"]',
            settingsButtonSelector: '[data-vc-ui-element="settings-dropdown-button"]',
            settingsDropdownSelector: '[data-vc-ui-element="settings-dropdown"]',
            settingsPresetId: null,
            uiEvents: {
                init: "addEvents",
                render: "hideDropdown",
                afterRender: "afterRenderActions"
            },
            afterRenderActions: function() {
                this.untaintSettingsPresetData(), this.showDropdown()
            },
            hideDropdown: function() {
                this.$el.find('[data-vc-ui-element="settings-dropdown"]').hide()
            },
            showDropdown: function() {
                var tag = this.model.get("shortcode");
                window.vc_settings_show && "vc_column" !== tag && this.$el.find('[data-vc-ui-element="settings-dropdown"]').show()
            },
            showDropdownMenu: function() {
                var tag = this.model.get("shortcode"),
                    $this = $(this);
                $this.data("vcSettingsMenuLoaded") && tag === $this.data("vcShortcodeName") || this.reloadSettingsMenuContent()
            },
            addEvents: function() {
                var $tab = this.$el.find(".vc_edit-form-tab.vc_active"),
                    tag = this.model.get("shortcode"),
                    _this = this;
                $(document).off("beforeMinimize.vc.paramWindow", this.minimizeButtonSelector).on("beforeMinimize.vc.paramWindow", this.minimizeButtonSelector, function() {
                    $tab.find(".vc_ui-prompt-presets .vc_ui-prompt-close").trigger("click")
                }), $(document).off("close.vc.paramWindow", this.closeButtonSelector).on("beforeClose.vc.paramWindow", this.closeButtonSelector, function() {
                    $tab.find(".vc_ui-prompt-presets .vc_ui-prompt-close").trigger("click")
                }), $(document).off("show.vc.accordion", this.settingsButtonSelector).on("show.vc.accordion", this.settingsButtonSelector, function() {
                    var $this = $(this);
                    $this.data("vcSettingsMenuLoaded") && tag === $this.data("vcShortcodeName") || _this.reloadSettingsMenuContent()
                })
            },
            saveSettingsAjaxData: function(shortcode_name, title, is_default, data) {
                return {
                    action: "vc_action_save_settings_preset",
                    shortcode_name: shortcode_name,
                    is_default: is_default ? 1 : 0,
                    vc_inline: !0,
                    title: title,
                    data: data,
                    _vcnonce: window.vcAdminNonce
                }
            },
            saveSettings: function(title, is_default) {
                var shortcode_name = this.model.get("shortcode"),
                    data = JSON.stringify(this.getParamsForSettingsPreset());
                if (void 0 !== title && title.length) return void 0 === is_default && (is_default = !1), this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.saveSettingsAjaxData(shortcode_name, title, is_default, data),
                    context: this
                }).done(function(response) {
                    response.success && (this.setSettingsMenuContent(response.html), this.settingsPresetId = response.id, this.untaintSettingsPresetData())
                }).always(this.resetAjax), this.ajax
            },
            fetchSaveSettingsDialogAjaxData: function() {
                return {
                    action: "vc_action_render_settings_preset_title_prompt",
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            fetchSaveSettingsDialog: function(callback) {
                var $contentContainer = this.$el.find(".vc_ui-panel-content-container");
                $contentContainer.find(".vc_ui-prompt-presets").length ? void 0 !== callback && callback(!1) : (this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.fetchSaveSettingsDialogAjaxData()
                }).done(function(response) {
                    response.success && ($contentContainer.prepend(response.html), void 0 !== callback) && callback(!0)
                }).fail(function() {
                    void 0 !== callback && callback(!1)
                }).always(this.resetAjax))
            },
            showSaveSettingsDialog: function(is_default) {
                var _this = this;
                this.isSettingsPresetDefault = !!is_default, this.fetchSaveSettingsDialog(function(created) {
                    var $btn, delay, $contentContainer = _this.$el.find(".vc_ui-panel-content-container"),
                        $prompt = $contentContainer.find(".vc_ui-prompt-presets"),
                        $title = $prompt.find(".textfield"),
                        $viewPresetsButton = ($contentContainer.find(".vc_ui-prompt.vc_visible").removeClass("vc_visible"), $prompt.find("[data-vc-view-settings-preset]"));
                    "undefined" !== window.vc_vendor_settings_presets[_this.model.get("shortcode")] ? $viewPresetsButton.removeAttr("disabled") : $viewPresetsButton.attr("disabled", "disabled"), $prompt.addClass("vc_visible"), $title.trigger("focus"), $contentContainer.addClass("vc_ui-content-hidden"), created && ($btn = $prompt.find("#vc_ui-save-preset-btn"), delay = 0, $prompt.on("submit", function() {
                        var title = $title.val();
                        return title.length && _this.saveSettings(title, _this.isSettingsPresetDefault).done(function(e) {
                            var data = this.getParamsForSettingsPreset();
                            $title.val(""), _this.setCustomButtonMessage($btn, void 0, void 0, !0), vc.events.trigger("vc:savePreset", e.id, _this.model.get("shortcode"), title, data), delay = _.delay(function() {
                                $prompt.removeClass("vc_visible"), $contentContainer.removeClass("vc_ui-content-hidden")
                            }, 5e3)
                        }).fail(function() {
                            _this.setCustomButtonMessage($btn, window.i18nLocale.ui_danger, "danger", !0)
                        }), !1
                    }), $prompt.on("click", ".vc_ui-prompt-close", function() {
                        return _this.checkAjax(), $prompt.removeClass("vc_visible"), $contentContainer.removeClass("vc_ui-content-hidden"), _this.clearCustomButtonMessage.call(this, $btn), delay && (window.clearTimeout(delay), delay = 0), !1
                    }))
                })
            },
            loadSettingsAjaxData: function(id) {
                return {
                    action: "vc_action_get_settings_preset",
                    vc_inline: !0,
                    id: id,
                    _vcnonce: window.vcAdminNonce
                }
            },
            loadSettings: function(id) {
                return this.panelInit = !1, this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.loadSettingsAjaxData(id),
                    context: this
                }).done(function(response) {
                    response.success && (this.settingsPresetId = id, this.applySettingsPreset(response.data))
                }).always(this.resetAjax), this.ajax
            },
            saveAsDefaultSettingsAjaxData: function(shortcode_name, id) {
                return {
                    action: "vc_action_set_as_default_settings_preset",
                    shortcode_name: shortcode_name,
                    id: id,
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            saveAsDefaultSettings: function(id, doneCallback) {
                var shortcode_name = this.model.get("shortcode"),
                    id = id || this.settingsPresetId;
                id ? (this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.saveAsDefaultSettingsAjaxData(shortcode_name, id),
                    context: this
                }).done(function(response) {
                    response.success && (this.setSettingsMenuContent(response.html), this.untaintSettingsPresetData(), doneCallback) && doneCallback()
                }).always(this.resetAjax)) : this.showSaveSettingsDialog(!0)
            },
            restoreDefaultSettingsAjaxData: function(shortcode_name) {
                return {
                    action: "vc_action_restore_default_settings_preset",
                    shortcode_name: shortcode_name,
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            restoreDefaultSettings: function() {
                var shortcode_name = this.model.get("shortcode");
                this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.restoreDefaultSettingsAjaxData(shortcode_name),
                    context: this
                }).done(function(response) {
                    response.success && this.setSettingsMenuContent(response.html)
                }).always(this.resetAjax)
            },
            setSettingsMenuContent: function(html) {
                var $button = this.$el.find(this.settingsButtonSelector),
                    $menu = this.$el.find(this.settingsMenuSelector),
                    shortcode_name = this.model.get("shortcode"),
                    _this = this;
                $button.data("vcShortcodeName", shortcode_name), $menu.html(html), window.vc_presets_data && 0 < window.vc_presets_data.presetsCount ? $menu.find("[data-vc-view-settings-preset]").removeAttr("disabled") : $menu.find("[data-vc-view-settings-preset]").attr("disabled", "disabled"), $menu.find("[data-vc-view-settings-preset]").on("click", function() {
                    _this.showViewSettingsList(), _this.closeSettings()
                }), $menu.find("[data-vc-save-settings-preset]").on("click", function() {
                    _this.showSaveSettingsDialog(), _this.closeSettings()
                }), $menu.find("[data-vc-save-template]").on("click", function() {
                    _this.showSaveTemplateDialog(), _this.closeSettings()
                }), $menu.find("[data-vc-save-default-settings-preset]").on("click", function() {
                    _this.saveAsDefaultSettings(), _this.closeSettings()
                }), $menu.find("[data-vc-restore-default-settings-preset]").on("click", function() {
                    _this.restoreDefaultSettings(), _this.closeSettings()
                })
            },
            reloadSettingsMenuContentAjaxData: function(shortcode_name) {
                return {
                    action: "vc_action_render_settings_preset_popup",
                    shortcode_name: shortcode_name,
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            showViewSettingsList: function() {
                var _this, $prompt, closePrompt, $contentContainer = this.$el.find(".vc_ui-panel-content-container");
                $contentContainer.find(".vc_ui-prompt-view-presets:not(.vc_visible)").remove(), $contentContainer.find(".vc_ui-prompt-view-presets").length || ($contentContainer.find(".vc_ui-prompt.vc_visible").removeClass("vc_visible"), _this = this, $prompt = jQuery('<form class="vc_ui-prompt vc_ui-prompt-view-presets"><div class="vc_ui-prompt-controls"><button type="button" class="vc_general vc_ui-control-button vc_ui-prompt-close"><i class="vc-composer-icon vc-c-icon-close"></i></button></div><div class="vc_ui-prompt-title"><label for="prompt_title" class="wpb_element_label">Elements</label></div><div class="vc_ui-prompt-content"><div class="vc_ui-prompt-column"><div class="vc_ui-template-list vc_ui-list-bar" data-vc-action="collapseAll" style="margin-top: 20px;" data-vc-presets-list-content></div></div></div>'), this.buildsettingsListContent($prompt), $prompt.appendTo($contentContainer), $prompt.addClass("vc_visible"), $contentContainer.addClass("vc_ui-content-hidden"), closePrompt = function() {
                    return $prompt.remove(), $contentContainer.removeClass("vc_ui-content-hidden"), !1
                }, $prompt.off("click.vc1").on("click.vc1", "[data-vc-load-settings-preset]", function(e) {
                    _this.loadSettings($(e.currentTarget).data("vcLoadSettingsPreset")), closePrompt()
                }), $prompt.off("click.vc4").on("click.vc4", "[data-vc-set-default-settings-preset]", function() {
                    _this.saveAsDefaultSettings($(this).data("vcSetDefaultSettingsPreset"), function() {
                        _this.buildsettingsListContent($prompt)
                    })
                }), $prompt.off("click.vc3").on("click.vc3", ".vc_ui-prompt-close", function() {
                    closePrompt(), _this.checkAjax()
                }))
            },
            buildsettingsListContent: function($prompt) {
                var itemsTemplate = vc.template('<div class="vc_ui-template"><div class="vc_ui-list-bar-item"><button class="vc_ui-list-bar-item-trigger" title="Apply Element" type="button" data-vc-load-settings-preset="<%- id %>"><%- title %></button><div class="vc_ui-list-bar-item-actions"><button class="vc_general vc_ui-control-button" title="Apply Element" type="button" data-vc-load-settings-preset="<%- id %>"><i class="vc-composer-icon vc-c-icon-add"></i></button><button class="vc_general vc_ui-control-button" title="Delete Element" type="button" data-vc-delete-settings-preset="<%- id %>"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></button></div></div></div>'),
                    $content = $prompt.find("[data-vc-presets-list-content]");
                $content.empty(), _.each(window.vc_presets_data.presets[0], function(item, id) {
                    var title = item;
                    0 < window.vc_presets_data.defaultId && parseInt(id, 10) === window.vc_presets_data.defaultId && (title = item + " (default)"), $content.append(itemsTemplate({
                        title: title,
                        id: id
                    }))
                }), _.each(window.vc_presets_data.presets[1], function(item, id) {
                    var title = item;
                    0 < window.vc_presets_data.defaultId && parseInt(id, 10) === window.vc_presets_data.defaultId && (title = item + " (default)"), $content.append(itemsTemplate({
                        title: title,
                        id: id
                    }))
                })
            },
            reloadSettingsMenuContent: function() {
                var shortcode_name = this.model.get("shortcode"),
                    $button = this.$el.find(this.settingsButtonSelector),
                    success = !1;
                return this.setSettingsMenuContent(""), this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.reloadSettingsMenuContentAjaxData(shortcode_name),
                    context: this
                }).done(function(response) {
                    response.success && (success = !0, this.setSettingsMenuContent(response.html), $button.data("vcSettingsMenuLoaded", !0))
                }).always(function() {
                    success || this.closeSettings(), this.resetAjax()
                }), this.ajax
            },
            closeSettings: function(destroy) {
                void 0 === destroy && (destroy = !1);
                var $menu = this.$el.find(this.settingsMenuSelector),
                    $button = this.$el.find(this.settingsButtonSelector);
                destroy && ($button.data("vcSettingsMenuLoaded", !1), $menu.html("")), $button.vcAccordion("hide")
            },
            isSettingsPresetDataTainted: function() {
                var params = (params = JSON.stringify(this.getParamsForSettingsPreset())).replace(/vc_custom_\d+/, "");
                return this.$el.data("vcSettingsPresetHash") !== vc_globalHashCode(params)
            },
            untaintSettingsPresetData: function() {
                var params = (params = JSON.stringify(this.getParamsForSettingsPreset())).replace(/vc_custom_\d+/, "");
                this.$el.data("vcSettingsPresetHash", vc_globalHashCode(params))
            },
            applySettingsPresetAjaxData: function(params) {
                var parent_id = this.model.get("parent_id");
                return {
                    action: "vc_edit_form",
                    tag: this.model.get("shortcode"),
                    parent_tag: parent_id ? vc.shortcodes.get(parent_id).get("shortcode") : null,
                    post_id: vc_post_id,
                    params: params,
                    _vcnonce: window.vcAdminNonce
                }
            },
            applySettingsPreset: function(params) {
                return this.currentModelParams = params, vc.events.trigger("presets:apply", this.model, params), this._killEditor(), this.trigger("render"), this.show(), this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: this.applySettingsPresetAjaxData(params),
                    context: this
                }).done(this.buildParamsContent).always(this.resetAjax), this
            },
            getParamsForSettingsPreset: function() {
                var shortcode = this.model.get("shortcode"),
                    params = this.getParams();
                return "vc_column" !== shortcode && "vc_column_inner" !== shortcode || (delete params.width, delete params.offset), params
            }
        }, vc.events.on("presets.apply", function(model, params) {
            return "vc_tta_section" === model.get("shortcode") && void 0 !== params.tab_id && (params.tab_id = vc_guid() + "-cl"), params
        })
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.ExtendTemplates = {
            fetchSaveTemplateDialogAjaxData: function() {
                return {
                    action: "vc_action_render_settings_templates_prompt",
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            },
            fetchSaveTemplateDialog: function(callback) {
                var $tab = this.$el.find(".vc_ui-panel-content-container");
                if (!$tab.find(".vc_ui-prompt-templates").length) return this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.fetchSaveTemplateDialogAjaxData()
                }).done(function(response) {
                    response.success && ($tab.prepend(response.html), void 0 !== callback) && callback(!0)
                }).always(this.resetAjax), this.ajax;
                void 0 !== callback && callback(!1)
            },
            showSaveTemplateDialog: function() {
                var _this = this;
                this.fetchSaveTemplateDialog(function(created) {
                    var delay, $btn, $tab = _this.$el.find(".vc_ui-panel-content-container"),
                        $prompt = $tab.find(".vc_ui-prompt-templates"),
                        $title = $prompt.find(".textfield");
                    $tab.find(".vc_ui-prompt.vc_visible").removeClass("vc_visible"), $prompt.addClass("vc_visible"), $title.trigger("focus"), $tab.addClass("vc_ui-content-hidden"), created && (delay = 0, $btn = $prompt.find("#vc_ui-save-templates-btn"), $prompt.on("submit", function() {
                        var title = $title.val();
                        _this.$el.find(_this.settingsButtonSelector);
                        return title.length && (title = {
                            action: vc.templates_panel_view.save_template_action,
                            template: vc.shortcodes.singleStringify(_this.model.get("id"), "template"),
                            template_name: title,
                            vc_inline: !0,
                            _vcnonce: window.vcAdminNonce
                        }, vc.templates_panel_view.reloadTemplateList(title, function() {
                            $title.val(""), _this.setCustomButtonMessage($btn, void 0, void 0, !0), delay = _.delay(function() {
                                $prompt.removeClass("vc_visible"), $tab.removeClass("vc_ui-content-hidden")
                            }, 5e3)
                        }, function() {
                            _this.setCustomButtonMessage($btn, window.i18nLocale.ui_danger, "danger")
                        })), !1
                    }), $prompt.on("click", ".vc_ui-prompt-close", function() {
                        return _this.checkAjax(), $prompt.removeClass("vc_visible"), $tab.removeClass("vc_ui-content-hidden"), _this.clearCustomButtonMessage.call(this, $btn), delay && (window.clearTimeout(delay), delay = 0), !1
                    }))
                })
            }
        }
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.EditElementPanelView = vc.PanelView.vcExtendUI(vc.HelperAjax).vcExtendUI(vc.ExtendPresets).vcExtendUI(vc.ExtendTemplates).vcExtendUI(vc.HelperPrompts).extend({
            panelName: "edit_element",
            el: "#vc_properties-panel",
            contentSelector: ".vc_ui-panel-content.vc_properties-list",
            minimizeButtonSelector: '[data-vc-ui-element="button-minimize"]',
            closeButtonSelector: '[data-vc-ui-element="button-close"]',
            titleSelector: ".vc_panel-title",
            tabsInit: !1,
            doCheckTabs: !0,
            $tabsMenu: !1,
            dependent_elements: {},
            mapped_params: {},
            draggable: !1,
            panelInit: !1,
            $spinner: !1,
            active_tab_index: 0,
            buttonMessageTimeout: !1,
            notRequestTemplate: !1,
            requiredParamsInitialized: !1,
            currentModelParams: !1,
            customButtonMessageTimeout: !1,
            events: {
                "click [data-save=true]": "save",
                "click [data-dismiss=panel]": "hide",
                "mouseover [data-transparent=panel]": "addOpacity",
                "click [data-transparent=panel]": "toggleOpacity",
                "mouseout [data-transparent=panel]": "removeOpacity"
            },
            initialize: function() {
                _.bindAll(this, "setSize", "setTabsSize", "fixElContainment", "hookDependent", "resetAjax", "removeAllPrompts"), this.on("setSize", this.setResize, this), this.on("render", this.resetMinimize, this), this.on("render", this.setTitle, this), this.on("render", this.prepareContentBlock, this)
            },
            setCustomButtonMessage: function($btn, message, type, showInBackend) {
                return void 0 === $btn && ($btn = this.$el.find('[data-vc-ui-element="button-save"]')), void 0 === showInBackend && (showInBackend = !1), this.clearCustomButtonMessage = _.bind(this.clearCustomButtonMessage, this), !showInBackend && !vc.frame_window || this.customButtonMessageTimeout || (void 0 === message && (message = window.i18nLocale.ui_saved), void 0 === type && (type = "success"), showInBackend = $btn.html(), $btn.addClass("vc_ui-button-" + type + " vc_ui-button-undisabled").removeClass("vc_ui-button-action").data("vcCurrentTextHtml", showInBackend).data("vcCurrentTextType", type).html(message), _.delay(this.clearCustomButtonMessage.bind(this, $btn), 5e3), this.customButtonMessageTimeout = !0), this
            },
            clearCustomButtonMessage: function($btn) {
                var type, currentTextHtml;
                this.customButtonMessageTimeout && (window.clearTimeout(this.customButtonMessageTimeout), currentTextHtml = $btn.data("vcCurrentTextHtml") || "Save", type = $btn.data("vcCurrentTextType"), $btn.html(currentTextHtml).removeClass("vc_ui-button-" + type + " vc_ui-button-undisabled").addClass("vc_ui-button-action"), this.customButtonMessageTimeout = !1)
            },
            render: function(model, not_request_template) {
                return this.$el.is(":hidden") && vc.closeActivePanel(), not_request_template && (this.notRequestTemplate = !0), this.model = model, this.currentModelParams = this.model.get("params"), (vc.active_panel = this).resetMinimize(), this.clicked = !1, this.$el.css("height", "auto"), this.$el.css("maxHeight", "75vh"), not_request_template = this.model.setting("params") || [], this.$el.attr("data-vc-shortcode", this.model.get("shortcode")), this.tabsInit = !1, this.panelInit = !1, this.active_tab_index = 0, this.requiredParamsInitialized = !1, this.mapped_params = {}, this.dependent_elements = {}, _.each(not_request_template, function(param) {
                    this.mapped_params[param.param_name] = param
                }, this), this.trigger("render"), this.show(), this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: this.ajaxData(),
                    context: this
                }).done(this.buildParamsContent).always(this.resetAjax), this
            },
            prepareContentBlock: function() {
                this.$content = this.notRequestTemplate ? this.$el : this.$el.find(this.contentSelector).removeClass("vc_with-tabs"), this.$content.empty(), this.$spinner = $('<span class="vc_ui-wp-spinner vc_ui-wp-spinner-lg vc_ui-wp-spinner-dark"></span>'), this.$content.prepend(this.$spinner)
            },
            buildParamsContent: function(data) {
                var $panelHeader, $panelTab, data = $(data),
                    $tabs = data.find('[data-vc-ui-element="panel-tabs-controls"]'),
                    _self = ($tabs.find(".vc_edit-form-tab-control:first-child").addClass("vc_active"), $panelHeader = this.$el.find('[data-vc-ui-element="panel-header-content"]'), ($panelTab = data.find('[data-vc-ui-element="panel-edit-element-tab"]')) && $panelTab.addClass("visually-hidden"), $panelHeader && $panelHeader.addClass("visually-hidden"), this.$content.html(data).append("<script async>window.setTimeout(function(){window.wpb_edit_form_loaded=true;},100);<\/script>"), this.$content.prepend(this.$spinner), $tabs.prependTo($panelHeader), this),
                    counter = 0,
                    cb = function() {
                        !window.wpb_edit_form_loaded && counter < 10 ? (counter++, setTimeout(cb, 100)) : (_self.$content.removeAttr("data-vc-param-initialized"), _self.active_tab_index = 0, _self.tabsInit = !1, _self.panelInit = !1, _self.dependent_elements = {}, _self.requiredParamsInitialized = !1, _self.$content.find("[data-vc-param-initialized]").removeAttr("data-vc-param-initialized"), $panelTab.removeClass("visually-hidden"), _self.$content.find(".vc_ui-wp-spinner").remove(), _self.init(), _self.$content.parent().scrollTop(1).scrollTop(0), _self.$content.removeClass("vc_properties-list-init"), _self.$el.trigger("vcPanel.shown"), _self.trigger("afterRender"))
                    };
                window.setTimeout(cb, 800)
            },
            resetMinimize: function() {
                this.$el.removeClass("vc_panel-opacity")
            },
            ajaxData: function() {
                var parent_id = this.model.get("parent_id"),
                    parent_id = parent_id ? this.model.collection.get(parent_id).get("shortcode") : null,
                    params = this.model.get("params"),
                    params = _.extend({}, vc.getDefaults(this.model.get("shortcode")), params);
                return {
                    action: "vc_edit_form",
                    tag: this.model.get("shortcode"),
                    parent_tag: parent_id,
                    post_id: vc_post_id,
                    params: params,
                    _vcnonce: window.vcAdminNonce
                }
            },
            init: function() {
                vc.EditElementPanelView.__super__.init.call(this), this.$el.find('[data-vc-ui-element="panel-header-content"]').removeClass("visually-hidden"), this.initParams(), this.initDependency(), $(".wpb_edit_form_elements .textarea_html").each(function() {
                    window.init_textarea_html($(this))
                }), this.trigger("init"), this.panelInit = !0
            },
            initParams: function() {
                var _this = this,
                    $content = this.content().find('#vc_edit-form-tabs [data-vc-ui-element="panel-edit-element-tab"]:eq(' + this.active_tab_index + ")");
                ($content = $content.length ? $content : this.content()).attr("data-vc-param-initialized") || ($('[data-vc-ui-element="panel-shortcode-param"]', $content).each(function() {
                    var param, $field = $(this);
                    $field.data("vcInitParam") || (param = $field.data("param_settings"), vc.atts.init.call(_this, param, $field), $field.data("vcInitParam", !0))
                }), $content.attr("data-vc-param-initialized", !0)), this.requiredParamsInitialized || _.isUndefined(vc.required_params_to_init) || ($('[data-vc-ui-element="panel-shortcode-param"]', this.content()).each(function() {
                    var param, $field = $(this);
                    !$field.data("vcInitParam") && -1 < _.indexOf(vc.required_params_to_init, $field.data("param_type")) && (param = $field.data("param_settings"), vc.atts.init.call(_this, param, $field), $field.data("vcInitParam", !0))
                }), this.requiredParamsInitialized = !0)
            },
            initDependency: function() {
                var callDependencies = {};
                _.each(this.mapped_params, function(param) {
                    var rules, $masters, $slave;
                    _.isObject(param) && _.isObject(param.dependency) && (rules = param.dependency, _.isString(param.dependency.element) && ($masters = $("[name=" + param.dependency.element + "].wpb_vc_param_value", this.$content), $slave = $("[name= " + param.param_name + "].wpb_vc_param_value", this.$content), _.each($masters, function(master) {
                        var master = $(master),
                            name = master.attr("name");
                        _.isArray(this.dependent_elements[master.attr("name")]) || (this.dependent_elements[master.attr("name")] = []), this.dependent_elements[master.attr("name")].push($slave), master.data("dependentSet") || (master.attr("data-dependent-set", "true"), master.off("keyup change", this.hookDependent).on("keyup change", this.hookDependent)), callDependencies[name] || (callDependencies[name] = master)
                    }, this)), _.isString(rules.callback)) && window[rules.callback].call(this)
                }, this), this.doCheckTabs = !1, _.each(callDependencies, function(obj) {
                    this.hookDependent({
                        currentTarget: obj
                    })
                }, this), this.doCheckTabs = !0, this.checkTabs(), callDependencies = null
            },
            hookDependent: function(e) {
                var is_empty, $master = $(e.currentTarget),
                    $master_container = $master.closest(".vc_column"),
                    dependent_elements = this.dependent_elements[$master.attr("name")],
                    master_value = $master.is(":checkbox") ? _.map(this.$content.find("[name=" + $(e.currentTarget).attr("name") + "].wpb_vc_param_value:checked"), function(element) {
                        return $(element).val()
                    }) : $master.val(),
                    e = this.doCheckTabs;
                return this.doCheckTabs = !1, is_empty = $master.is(":checkbox") ? !this.$content.find("[name=" + $master.attr("name") + "].wpb_vc_param_value:checked").length : !master_value.length, $master_container.hasClass("vc_dependent-hidden") ? _.each(dependent_elements, function($element) {
                    var event = jQuery.Event("change");
                    event.extra_type = "vcHookDepended", $element.closest(".vc_column").addClass("vc_dependent-hidden"), $element.trigger(event)
                }) : _.each(dependent_elements, function($element) {
                    var param_name = $element.attr("name"),
                        param_name = _.isObject(this.mapped_params[param_name]) && _.isObject(this.mapped_params[param_name].dependency) ? this.mapped_params[param_name].dependency : {},
                        $param_block = $element.closest(".vc_column"),
                        param_name = (_.isBoolean(param_name.not_empty) && !0 === param_name.not_empty && !is_empty || _.isBoolean(param_name.is_empty) && !0 === param_name.is_empty && is_empty || param_name.value && _.intersection(_.isArray(param_name.value) ? param_name.value : [param_name.value], _.isArray(master_value) ? master_value : [master_value]).length || param_name.value_not_equal_to && !_.intersection(_.isArray(param_name.value_not_equal_to) ? param_name.value_not_equal_to : [param_name.value_not_equal_to], _.isArray(master_value) ? master_value : [master_value]).length ? $param_block.removeClass("vc_dependent-hidden") : $param_block.addClass("vc_dependent-hidden"), jQuery.Event("change"));
                    param_name.extra_type = "vcHookDepended", $element.trigger(param_name)
                }, this), e && (this.checkTabs(), this.doCheckTabs = !0), this
            },
            checkTabs: function() {
                var that = this;
                !1 === this.tabsInit && (this.tabsInit = !0, this.$content.hasClass("vc_with-tabs")) && (this.$tabsMenu = this.$content.find(".vc_edit-form-tabs-menu")), this.$tabsMenu && (this.$content.find('[data-vc-ui-element="panel-edit-element-tab"]').each(function(index) {
                    var $tabControl = that.$tabsMenu.find('> [data-tab-index="' + index + '"]');
                    $(this).find('[data-vc-ui-element="panel-shortcode-param"]:not(".vc_dependent-hidden")').length ? $tabControl.hasClass("vc_dependent-hidden") && ($tabControl.removeClass("vc_dependent-hidden").removeClass("vc_tab-color-animated").addClass("vc_tab-color-animated"), window.setTimeout(function() {
                        $tabControl.removeClass("vc_tab-color-animated")
                    }, 200)) : $tabControl.addClass("vc_dependent-hidden")
                }), window.setTimeout(this.setTabsSize, 100))
            },
            setTabsSize: function() {
                this.$tabsMenu.parents(".vc_with-tabs.vc_panel-body").css("margin-top", this.$tabsMenu.outerHeight())
            },
            setActive: function() {
                this.$el.prev().addClass("active")
            },
            window: function() {
                return window
            },
            getParams: function() {
                var paramsSettings = this.mapped_params;
                return this.params = _.extend({}, this.model.get("params")), _.each(paramsSettings, function(param) {
                    var value = vc.atts.parseFrame.call(this, param);
                    this.params[param.param_name] = value
                }, this), _.each(vc.edit_form_callbacks, function(callback) {
                    callback.call(this)
                }, this), this.params
            },
            content: function() {
                return this.$content
            },
            save: function() {
                var shortcode, params, mergedParams;
                this.panelInit && (shortcode = this.model.get("shortcode"), params = this.getParams(), mergedParams = _.extend({}, vc.getDefaults(shortcode), vc.getMergedParams(shortcode, params)), _.isUndefined(params.content) || (mergedParams.content = params.content), this.model.save({
                    params: mergedParams
                }), this.showMessage(window.sprintf(window.i18nLocale.inline_element_saved, vc.getMapped(shortcode).name), "success"), vc.frame_window || this.hide(), this.trigger("save"))
            },
            show: function() {
                this.$el.hasClass("vc_active") || (this.$el.addClass("vc_active"), this.draggable || this.initDraggable(), this.fixElContainment(), this.trigger("show"))
            },
            hide: function(e) {
                e && e.preventDefault && e.preventDefault(), this.checkAjax(), this.ajax = !1, this.model && (this.model = null), vc.active_panel = !1, this.currentModelParams = !1, this._killEditor(), this.$el.removeClass("vc_active"), this.$el.find(".vc_properties-list").removeClass("vc_with-tabs").css("margin-top", "auto"), this.$content.empty(), this.trigger("hide")
            },
            setTitle: function() {
                return this.$el.find(this.titleSelector).html(vc.getMapped(this.model.get("shortcode")).name + " " + window.i18nLocale.settings), this
            },
            _killEditor: function() {
                _.isUndefined(window.tinyMCE) || $("textarea.textarea_html", this.$el).each(function() {
                    var id = $(this).attr("id");
                    "4" === tinymce.majorVersion ? window.tinyMCE.execCommand("mceRemoveEditor", !0, id) : window.tinyMCE.execCommand("mceRemoveControl", !0, id)
                }), jQuery("body").off("click.wpcolorpicker")
            }
        }), window.vc.EditElementUIPanel = vc.EditElementPanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewResizable).vcExtendUI(vc.HelperPanelViewDraggable).extend({
            el: "#vc_ui-panel-edit-element",
            events: {
                'click [data-vc-ui-element="button-save"]': "save",
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
                'click [data-vc-ui-element="panel-tab-control"]': "changeTab"
            },
            titleSelector: '[data-vc-ui-element="panel-title"]',
            initialize: function() {
                vc.EditElementUIPanel.__super__.initialize.call(this), this.on("afterResizeStart", function() {
                    this.$el.css("maxHeight", "none")
                })
            },
            show: function() {
                vc.EditElementUIPanel.__super__.show.call(this), $('[data-vc-ui-element="panel-tabs-controls"]', this.$el).remove(), this.$el.css("maxHeight", "75vh")
            },
            tabsMenu: function() {
                var $tabsMenu;
                return !1 === this.tabsInit && (this.tabsInit = !0, ($tabsMenu = this.$el.find('[data-vc-ui-element="panel-tabs-controls"]')).length) && (this.$tabsMenu = $tabsMenu), this.$tabsMenu
            },
            buildTabs: function() {
                this.content().find('[data-vc-ui-element="panel-tabs-controls"]').prependTo('[data-vc-ui-element="panel-header-content"]')
            },
            changeTab: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = $(e.currentTarget);
                e.parent().hasClass("vc_active") || (this.$el.find('[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])').removeClass("vc_active"), e.parent().addClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_active').removeClass("vc_active"), this.active_tab_index = this.$el.find(e.data("vcUiElementTarget")).addClass("vc_active").index(), this.initParams(), this.$tabsMenu && this.$tabsMenu.vcTabsLine("checkDropdownContainerActive"), this.$content.parent().scrollTop(1).scrollTop(0), this.trigger("tabChange"))
            },
            checkTabs: function() {
                var _this = this;
                !1 === this.tabsInit && (this.tabsInit = !0, this.$tabsMenu = this.$el.find('[data-vc-ui-element="panel-tabs-controls"]')), this.tabsMenu() && (this.content().find('[data-vc-ui-element="panel-edit-element-tab"]').each(function(index) {
                    var $tabControl = _this.$tabsMenu.find('> [data-tab-index="' + index + '"]');
                    $(this).find('[data-vc-ui-element="panel-shortcode-param"]:not(".vc_dependent-hidden")').length ? $tabControl.hasClass("vc_dependent-hidden") && ($tabControl.removeClass("vc_dependent-hidden"), window.setTimeout(function() {
                        $tabControl.removeClass("vc_tab-color-animated")
                    }, 200)) : window.setTimeout(function() {
                        $tabControl.addClass("vc_dependent-hidden")
                    }, 0)
                }), this.$tabsMenu.vcTabsLine("refresh"), this.$tabsMenu.vcTabsLine("moveTabs"))
            }
        })
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.TemplateLibraryView = vc.PanelView.vcExtendUI(vc.HelperAjax).extend({
            myTemplates: [],
            $mainPopup: !1,
            $loadingPage: !1,
            $gridContainer: !1,
            $errorMessageContainer: !1,
            $myTemplateContainer: !1,
            $popupItems: !1,
            $previewImage: !1,
            $previewTitle: !1,
            $previewUpdate: !1,
            $previewDownload: !1,
            $previewUpdateBtn: !1,
            $previewDownloadBtn: !1,
            $templatePreview: !1,
            $templatePage: !1,
            $downloadPage: !1,
            $updatePage: !1,
            $content: !1,
            $filter: !1,
            compiledGridTemplate: !1,
            compiledTemplate: !1,
            loaded: !1,
            data: !1,
            events: {
                "click [data-dismiss=panel]": "hide",
                "click .vc_ui-panel-close-button": "closePopupButton",
                "click .vc_ui-access-library-btn": "accessLibrary",
                "click #vc_template-library-template-grid .vc_ui-panel-template-preview-button": "previewButton",
                "click .vc_ui-panel-back-button": "backToTemplates",
                "click .vc_ui-panel-template-download-button, #vc_template-library-download-btn": "downloadButton",
                "click .vc_ui-panel-template-update-button, #vc_template-library-update-btn": "updateButton",
                "keyup #vc_template_lib_name_filter": "filterTemplates",
                "search #vc_template_lib_name_filter": "filterTemplates"
            },
            initialize: function() {
                _.bindAll(this, "loadLibrary", "addTemplateStatus", "loadMyTemplates", "deleteTemplate"), this.$mainPopup = this.$el.find(".vc_ui-panel-popup"), this.$loadingPage = this.$el.find(".vc_ui-panel-loading"), this.$gridContainer = this.$el.find("#vc_template-library-template-grid"), this.$errorMessageContainer = this.$el.find("#vc_template-library-panel-error-message"), this.$myTemplateContainer = this.$el.find("#vc_template-library-shared_templates"), this.$popupItems = this.$el.find(".vc_ui-panel-popup-item"), this.$previewImage = this.$el.find(".vc_ui-panel-preview-image"), this.$previewTitle = this.$el.find(".vc_ui-panel-template-preview .vc_ui-panel-title"), this.$previewUpdate = this.$el.find("#vc_template-library-update"), this.$previewDownload = this.$el.find("#vc_template-library-download"), this.$previewUpdateBtn = this.$previewUpdate.find("#vc_template-library-update-btn"), this.$previewDownloadBtn = this.$previewUpdate.find("#vc_template-library-download-btn"), this.$templatePreview = this.$el.find(".vc_ui-panel-template-preview"), this.$templatePage = this.$el.find(".vc_ui-panel-template-content"), this.$downloadPage = this.$el.find(".vc_ui-panel-download"), this.$updatePage = this.$el.find(".vc_ui-panel-update"), this.$filter = this.$el.find("#vc_template_lib_name_filter"), this.$content = this.$el.find(".vc_ui-templates-content");
                var gridTemplateHtml = $("#vc_template-grid-item").html(),
                    gridTemplateHtml = (this.compiledGridTemplate = vc.template(gridTemplateHtml), $("#vc_template-item").html());
                this.compiledTemplate = vc.template(gridTemplateHtml), window.vc.events.on("templates:delete", this.deleteTemplate)
            },
            getLibrary: function() {
                var data, _this;
                this.loaded ? this.showLibrary() : (this.checkAjax(), data = this.getStorage("templates"), _this = this, data && "object" == typeof data && !_.isEmpty(data) ? (this.loaded = !0, this.loadLibrary(data), this.showLibrary()) : this.ajax = $.getJSON("https://vc-cc-templates.wpbakery.com/templates.json").done(function(data) {
                    _this.setStorage("templates", data), _this.loaded = !0, _this.loadLibrary(data), _this.showLibrary()
                }).always(this.resetAjax))
            },
            removeStorage: function(name) {
                try {
                    localStorage.removeItem("vc4-" + name), localStorage.removeItem("vc4-" + name + "_expiresIn")
                } catch (e) {
                    return !1
                }
                return !0
            },
            getStorage: function(key) {
                var now = Date.now(),
                    expiresIn = localStorage.getItem("vc4-" + key + "_expiresIn");
                if ((expiresIn = null == expiresIn ? 0 : expiresIn) < now) return this.removeStorage(key), null;
                try {
                    return JSON.parse(localStorage.getItem("vc4-" + key))
                } catch (e) {
                    return null
                }
            },
            setStorage: function(key, value, expires) {
                expires = null == expires ? 86400 : Math.abs(expires);
                expires = Date.now() + 1e3 * expires;
                try {
                    localStorage.setItem("vc4-" + key, JSON.stringify(value)), localStorage.setItem("vc4-" + key + "_expiresIn", expires)
                } catch (err) {
                    return window.console && window.console.warn && window.console.warn("template setStorage error", err), !1
                }
                return !0
            },
            loadLibrary: function(data) {
                var renderedOutput, _this;
                data && (renderedOutput = "", (_this = this).loaded = !0, this.data = data, this.$filter.val(""), data.forEach(function(item) {
                    item = _this.addTemplateStatus(item), renderedOutput += _this.compiledGridTemplate({
                        id: item.id,
                        title: item.title,
                        thumbnailUrl: item.thumbnailUrl,
                        previewUrl: item.previewUrl,
                        status: item.status,
                        downloaded: _.find(_this.myTemplates, {
                            id: item.id
                        }),
                        version: item.version
                    })
                }), this.$gridContainer.html(renderedOutput))
            },
            showLibrary: function() {
                this.$loadingPage.addClass("vc_ui-hidden"), this.$mainPopup.removeClass("vc_ui-hidden"), this.$templatePage.removeClass("vc_ui-hidden")
            },
            addTemplateStatus: function(template) {
                var status, statusHtml = "",
                    myTemplate = _.find(this.myTemplates, {
                        id: template.id
                    });
                return myTemplate && (status = window.i18nLocale.ui_template_downloaded, statusHtml = '<span class="vc_ui-panel-template-item-info"><span>' + (status = template.version > myTemplate.version ? window.i18nLocale.ui_template_fupdate : status) + "</span></span>"), template.status = statusHtml, template
            },
            loadMyTemplates: function() {
                var renderedOutput = "",
                    _this = this;
                this.myTemplates.forEach(function(item) {
                    renderedOutput += _this.compiledTemplate({
                        post_id: item.post_id,
                        title: item.title
                    })
                }), this.$myTemplateContainer.html(renderedOutput)
            },
            closePopupButton: function(e) {
                e && e.preventDefault && e.preventDefault(), this.$mainPopup.toggleClass("vc_ui-hidden"), this.$popupItems.addClass("vc_ui-hidden"), this.$content.removeClass("vc_ui-hidden")
            },
            accessLibrary: function() {
                this.$loadingPage.removeClass("vc_ui-hidden"), this.$content.addClass("vc_ui-hidden"), this.getLibrary()
            },
            previewButton: function(e) {
                var e = $(e.currentTarget),
                    imgUrl = e.data("preview-url"),
                    title = e.data("title"),
                    templateId = e.data("template-id"),
                    e = e.data("template-version"),
                    imgUrl = (this.$previewImage.attr("src", imgUrl), this.$previewTitle.text(title), _.find(this.myTemplates, {
                        id: templateId
                    }));
                this.$previewUpdate.toggleClass("vc_ui-hidden", !(imgUrl && imgUrl.version < e)), this.$previewDownload.toggleClass("vc_ui-hidden", !!imgUrl), this.$previewUpdateBtn.data("template-id", templateId), this.$previewDownloadBtn.data("template-id", templateId), this.$popupItems.addClass("vc_ui-hidden"), this.$templatePreview.removeClass("vc_ui-hidden"), this.$templatePreview.attr("data-template-id", templateId)
            },
            backToTemplates: function() {
                this.$popupItems.addClass("vc_ui-hidden"), this.$templatePage.removeClass("vc_ui-hidden")
            },
            deleteTemplate: function(data) {
                "shared_templates" === data.type && -1 !== (data = _.findIndex(this.myTemplates, {
                    post_id: data.id
                })) && (this.myTemplates.splice(data, 1), this.loaded) && this.loadLibrary(this.data)
            },
            downloadButton: function(e) {
                e && e.preventDefault && e.preventDefault();
                e = jQuery(e.currentTarget).closest("[data-template-id]").data("templateId");
                e && (this.showDownloadOverlay(), this.downloadTemplate(e))
            },
            updateButton: function(e) {
                e && e.preventDefault && e.preventDefault(), jQuery(e.currentTarget).closest("[data-template-id]").data("templateId") && this.showUpdateOverlay()
            },
            showDownloadOverlay: function() {
                this.$popupItems.addClass("vc_ui-hidden"), this.$downloadPage.removeClass("vc_ui-hidden")
            },
            hideDownloadOverlay: function(message) {
                message ? (this.$errorMessageContainer.html(message), this.$errorMessageContainer.removeClass("vc_ui-hidden")) : this.$errorMessageContainer.addClass("vc_ui-hidden"), this.$downloadPage.addClass("vc_ui-hidden"), this.$templatePage.removeClass("vc_ui-hidden")
            },
            showUpdateOverlay: function() {
                this.$popupItems.addClass("vc_ui-hidden"), this.$updatePage.removeClass("vc_ui-hidden")
            },
            hideUpdateOverlay: function(message) {
                this.$updatePage.addClass("vc_ui-hidden"), this.$templatePage.removeClass("vc_ui-hidden")
            },
            downloadTemplate: function(id) {
                this.checkAjax();
                var fail = !0;
                this.ajax = $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: {
                        action: "vc_shared_templates_download",
                        id: id,
                        _vcnonce: window.vcAdminNonce
                    },
                    dataType: "json",
                    context: this
                }).done(function(response) {
                    var template;
                    response && response.success && (template = _.find(this.data, {
                        id: id
                    })) && (fail = !1, template.post_id = response.data.post_id, this.myTemplates.unshift(template), this.loadMyTemplates(), this.loadLibrary(this.data), this.showLibrary())
                }).always(function(response, status) {
                    var message = "";
                    "success" === status && !fail || (message = response && response.data && response.data.message ? response.data.message : window.i18nLocale.ui_templates_failed_to_download), this.hideDownloadOverlay(message), this.resetAjax()
                })
            },
            filterTemplates: function() {
                var filter = ".vc_ui-panel-template-item .vc_ui-panel-template-item-name:containsi('" + this.$filter.val() + "')";
                $(".vc_ui-panel-template-item.vc_ui-visible", this.$gridContainer).removeClass("vc_ui-visible"), $(filter, this.$gridContainer).closest(".vc_ui-panel-template-item").addClass("vc_ui-visible")
            }
        }), $(function() {
            window.vcTemplatesLibraryData && (window.vc.templatesLibrary = new vc.TemplateLibraryView({
                el: '[data-vc-ui-element="panel-edit-element-tab"][data-tab="shared_templates"]'
            }), window.vc.templatesLibrary.myTemplates = window.vcTemplatesLibraryData.templates || [], window.vc.templatesLibrary.loadMyTemplates())
        })
    }(window.jQuery),
    function($) {
        "use strict";
        vc.PostSettingsUIPanelFrontendEditor = vc.PostSettingsPanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewResizable).vcExtendUI(vc.HelperPanelViewDraggable).vcExtendUI({
            panelName: "post_settings",
            uiEvents: {
                setSize: "setEditorSize",
                show: "setEditorSize"
            },
            setSize: function() {
                this.trigger("setSize")
            },
            setDefaultHeightSettings: function() {
                this.$el.css("height", "75vh")
            },
            setEditorSize: function() {
                this.editor_css.setSizeResizable(), this.editor_js_header.setSizeResizable(), this.editor_js_footer.setSizeResizable()
            }
        }), vc.PostSettingsUIPanelBackendEditor = vc.PostSettingsPanelViewBackendEditor.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewResizable).vcExtendUI(vc.HelperPanelViewDraggable).vcExtendUI(vc.HelperPanelSettingsPostCustomLayout).vcExtendUI({
            uiEvents: {
                setSize: "setEditorSize",
                show: "setEditorSize",
                render: "removeChangeTitleField"
            },
            setSize: function() {
                this.trigger("setSize")
            },
            setEditorSize: function() {
                this.editor_css.setSizeResizable(), this.editor_js_header.setSizeResizable(), this.editor_js_footer.setSizeResizable()
            },
            setDefaultHeightSettings: function() {
                this.$el.css("height", "75vh")
            },
            removeChangeTitleField: function() {
                $("#vc_settings-title-container").remove()
            }
        })
    }(window.jQuery),
    function() {
        "use strict";
        var events = {
            'click [data-vc-ui-element="button-save"]': "save",
            'click [data-vc-ui-element="button-close"]': "hide",
            'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
            'click [data-vc-ui-element="button-layout"]': "setLayout",
            'click [data-vc-ui-element="button-update-layout"]': "updateFromInput"
        };
        vc.RowLayoutUIPanelFrontendEditor = vc.RowLayoutEditorPanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewDraggable).extend({
            panelName: "rowLayouts",
            events: events
        }), vc.RowLayoutUIPanelBackendEditor = vc.RowLayoutEditorPanelViewBackend.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewDraggable).extend({
            panelName: "rowLayouts",
            events: events
        })
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.PresetSettingsUIPanelFrontendEditor = vc.PanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperAjax).vcExtendUI({
            panelName: "preset_settings",
            showMessageDisabled: !1,
            events: {
                'click [data-vc-ui-delete="preset-title"]': "removePreset",
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
                "click [data-vc-ui-add-preset]": "createPreset"
            },
            initialize: function(options) {
                this.frontEnd = options && options.frontEnd
            },
            createPreset: function(e) {
                var options, columnOptions, preset, column_params, rowOptions;
                _.isUndefined(vc.ShortcodesBuilder) || (this.builder = new vc.ShortcodesBuilder), preset = (e = $(e.currentTarget)).data("preset"), e = e.data("tag"), column_params = {
                    width: "1/1"
                }, rowOptions = {
                    shortcode: "vc_row",
                    params: {}
                }, this.frontEnd ? (this.builder.create(rowOptions), columnOptions = {
                    shortcode: "vc_column",
                    params: column_params,
                    parent_id: this.builder.lastID()
                }, this.builder.create(columnOptions), options = {
                    shortcode: e,
                    parent_id: this.builder.lastID()
                }, preset && (options.preset = preset), window.vc.closeActivePanel(), this.builder.create(options), this.model = this.builder.last(), this.builder.render()) : (columnOptions = {
                    shortcode: "vc_column",
                    params: column_params,
                    parent_id: (column_params = vc.shortcodes.create(rowOptions)).id,
                    root_id: column_params.id
                }, options = {
                    shortcode: e,
                    parent_id: vc.shortcodes.create(columnOptions).id,
                    root_id: column_params.id
                }, preset && (options.preset = preset), rowOptions = vc.shortcodes.create(options), window.vc.closeActivePanel(), this.model = rowOptions), _.isBoolean(vc.getMapped(e).show_settings_on_create) && !1 === vc.getMapped(e).show_settings_on_create || this.showEditForm()
            },
            showEditForm: function() {
                window.vc.edit_element_block_view.render(this.model)
            },
            render: function() {
                return this.$el.css("left", ($(window).width() - this.$el.width()) / 2), this
            },
            removePreset: function(e) {
                e && e.preventDefault && e.preventDefault();
                var closestPreset = jQuery(e.currentTarget).closest('[data-vc-ui-delete="preset-title"]'),
                    presetId = closestPreset.data("preset"),
                    closestPreset = closestPreset.data("preset-parent");
                this.deleteSettings(presetId, closestPreset, e)
            },
            deleteSettings: function(id, shortcode_name) {
                var _this = this;
                return !!confirm(window.i18nLocale.delete_preset_confirmation) && (this.checkAjax(), this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: this.deleteSettingsAjaxData(shortcode_name, id),
                    context: this
                }).done(function(response) {
                    response && response.success && (this.showMessage(window.i18nLocale.preset_removed, "success"), _this.$el.find('[data-preset="' + id + '"]').closest(".vc_ui-template").remove(), window.vc.events.trigger("vc:deletePreset", id))
                }).always(this.resetAjax), this.ajax)
            },
            deleteSettingsAjaxData: function(shortcode_name, id) {
                return {
                    action: "vc_action_delete_settings_preset",
                    shortcode_name: shortcode_name,
                    vc_inline: !0,
                    id: id,
                    _vcnonce: window.vcAdminNonce
                }
            },
            showMessage: function(text, type) {
                if (this.showMessageDisabled) return !1;
                this.message_box_timeout && (this.$el.find("[data-vc-panel-message]").remove(), window.clearTimeout(this.message_box_timeout)), this.message_box_timeout = !1;
                var $messageBox, messageBoxTemplate = vc.template('<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>"><div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>');
                switch (type) {
                    case "error":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "danger",
                            icon: "times",
                            text: text
                        }));
                        break;
                    case "warning":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "warning",
                            icon: "exclamation-triangle",
                            text: text
                        }));
                        break;
                    case "success":
                        $messageBox = $('<div class="vc_col-xs-12 wpb_element_wrapper" data-vc-panel-message>').html(messageBoxTemplate({
                            color: "success",
                            icon: "check",
                            text: text
                        }))
                }
                $messageBox.prependTo(this.$el.find(".vc_properties-list")), $messageBox.fadeIn(), this.message_box_timeout = window.setTimeout(function() {
                    $messageBox.remove()
                }, 6e3)
            }
        })
    }(window.jQuery), window.vc || (window.vc = {}),
    function($) {
        "use strict";
        window.vc.PostSettingsSeoUIPanel = vc.PostSettingsSeoUIPanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperPanelViewResizable).vcExtendUI(vc.HelperPanelViewDraggable).extend({
            el: "#vc_ui-panel-post-seo",
            panelName: "post_seo",
            events: {
                'click [data-vc-ui-element="button-close"]': "hide",
                'click [data-vc-ui-element="panel-tab-control"]': "changeTab",
                'click [data-vc-ui-element="button-save"]': "save",
                'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
                "click .vc_icon-remove": "removeImage",
                "change #vc_ui-seo-social .gallery_widget_attached_images_ids": "updateImagePreview",
                "input #social-title-x, #social-title-facebook": "updateTitlePreview",
                "input #social-description-x, #social-description-facebook": "updateDescriptionPreview",
                "click #preview-dots, #vc_seo-title, #vc_description-container": "focusTarget",
                'change .vc-preview-radio input[type="radio"]': "changePreviewMode",
                "input #vc_seo-title-field, #vc_seo-description-field, #vc_seo-slug-field": "updateGeneralPreviewText",
                "blur #vc_seo-title-field, #vc_seo-description-field": "fillSocialInputs",
                "change #vc_focus-keyphrase-field, #vc_seo-title-field, #vc_seo-description-field, #vc_seo-slug-field, #social-title-facebook, #social-description-facebook, #social-title-x, #social-description-x": "handleInputChange"
            },
            initialize: function() {
                _.bindAll(this, "fixElContainment", "setSize"), this.on("setSize", this.setResize, this), this.setFormDataState()
            },
            render: function(model, prepend) {
                this.$el.is(":hidden") && vc.closeActivePanel(), (vc.active_panel = this).show()
            },
            show: function() {
                var $tabs;
                this.$el.hasClass("vc_active") || (this.$el.addClass("vc_active"), this.draggable || this.initDraggable(), this.fixElContainment(), this.trigger("show"), ($tabs = this.$el.find(".vc_panel-tab")).length && (this.$tabs = $tabs))
            },
            changeTab: function(e) {
                e.preventDefault();
                e = $(e.currentTarget).parent(), $('[data-vc-ui-element="panel-add-element-tab"].vc_active').removeClass("vc_active"), e.addClass("vc_active"), this.$tabs.filter(".vc_active").removeClass("vc_active"), e = e.data("tabIndex");
                this.$tabs.filter('[data-tab-index="' + e + '"]').addClass("vc_active")
            },
            removeImage: function(e) {
                var $control = $(e.currentTarget),
                    wrapper = $control.closest(".edit_form_line"),
                    socialNetSlug = wrapper.attr("data-social-net-preview-slug"),
                    socialNetSlug = $("#" + socialNetSlug);
                socialNetSlug.find(".wpb-social-placeholder-image").show(), socialNetSlug.find("img").attr("src", ""), wrapper.find(".gallery_widget_attached_images_ids").val(""), e && e.preventDefault && e.preventDefault(), $control.parent().remove()
            },
            updateImagePreview: function(e) {
                var image, e = $(e.currentTarget).closest(".edit_form_line"),
                    src = e.find(".inner img").attr("src"),
                    e = e.attr("data-social-net-preview-slug");
                e && src && (src = src.replace("-150x150", ""), (image = (e = $("#" + e)).find("img")).attr("src", src), image.show(), e.find(".wpb-social-placeholder-image").hide())
            },
            updateTitlePreview: function(e) {
                e = $(e.currentTarget);
                e.closest(".vc_seo-social-block").find(".wpb-social-net-preview .vc_social-title").text(e.val())
            },
            updateDescriptionPreview: function(e) {
                var e = $(e.currentTarget),
                    wrapper = e.closest(".vc_seo-social-block"),
                    e = e.val();
                wrapper.find(".wpb-social-net-preview .vc_social-description").text(e), wrapper.find(".vc_social-description-counter").text(e.length)
            },
            focusTarget: function(e) {
                e = $(e.currentTarget).data("focus");
                $("#" + e).focus()
            },
            changePreviewMode: function(e) {
                var pagePreview = this.$el.find(".page-preview");
                "mobile" === $(e.currentTarget).val() ? pagePreview.removeClass("desktop-view") : pagePreview.addClass("desktop-view")
            },
            createSlug: function(inputString) {
                inputString = inputString.toLowerCase();
                return inputString = (inputString = (inputString = inputString.replace(/\s+/g, "-")).replace(/[^a-zA-Z0-9\-]/g, "")).replace(/^[.,!?()[]{}<>:;]+|[.,!?()[]{}<>:;]+$/g, "")
            },
            updateGeneralPreviewText: function(e) {
                var value = $(e.currentTarget).val(),
                    e = ("vc_seo-slug-field" === $(e.currentTarget).attr("id") && (value = this.createSlug(value)), $(e.currentTarget).data("preview")),
                    e = this.$el.find("#" + e);
                e && e.text(value)
            },
            handleInputChange: function(e) {
                var trimmedValue = e.target.value.trim();
                vc.seo_storage.setResults(trimmedValue, e.target.name, "formData")
            },
            setFormDataState: function() {
                this.$el.find("#vc_ui-seo-general").find('input[type="text"], textarea').each(function(index, input) {
                    var inputName = $(input).attr("name"),
                        input = $(input).val();
                    vc.seo_storage.setResults(input, inputName, "formData")
                }), this.$el.find("#vc_ui-seo-social").find('input[type="text"], textarea').each(function(index, input) {
                    var inputName = $(input).attr("name"),
                        input = $(input).val();
                    vc.seo_storage.setResults(input, inputName, "formData")
                })
            },
            fillSocialInputs: function(e) {
                var value = $(e.currentTarget).val(),
                    e = $(e.currentTarget).attr("name"),
                    formData = vc.seo_storage.get("formData"),
                    fieldMap = {
                        title: ["social-title-x", "social-title-facebook"],
                        description: ["social-description-x", "social-description-facebook"]
                    };
                fieldMap[e] && fieldMap[e].forEach(function(field) {
                    formData[field] || $("#" + field).val(value).trigger("input")
                })
            }
        })
    }(window.jQuery), window.vc || (window.vc = {}),
    function($) {
        "use strict";
        vc.SeoAnalysisView = Backbone.View.extend({
            $wpbContentWrapper: null,
            $navbarIcon: null,
            currentBadge: "",
            initialize: function() {
                this.$wpbContentWrapper = this.getContentWrapper(), this.$navbarIcon = $("#vc_seo-button"), vc.seo_utils.createMeasurementElement(), vc.seo_checks.analyzeContent(this.$wpbContentWrapper), this.render(), this.setIconBadge(), this.setEvents()
            },
            render: function() {
                vc.seo_checks.analyzeContent(this.$wpbContentWrapper);
                var resultsElements = this.getNotificationsHtml();
                this.$el.html(resultsElements), this.setIconBadge()
            },
            setEvents: function() {
                this.debouncedRender = _.debounce(this.render, 200), this.listenTo(this.model, "formData", this.debouncedRender), "admin_frontend_editor" === window.vc_mode ? (vc.events.on("afterRender", this.debouncedRender, this), vc.events.on("shortcodeView:updated", this.debouncedRender, this), vc.events.on("afterLoadShortcode", this.debouncedRender, this)) : (vc.events.on("shortcodes:update", this.debouncedRender, this), vc.events.on("shortcodes:add", this.debouncedRender, this), vc.events.on("undoredo:undo", this.debouncedRender, this), vc.events.on("undoredo:redo", this.debouncedRender, this)), vc.events.on("shortcodes:destroy", this.debouncedRender, this)
            },
            getNotificationsHtml: function() {
                var _this = this,
                    results = this.model.get("results"),
                    newResults = {
                        success: [],
                        problems: [],
                        warnings: []
                    },
                    resultsElements = (results.forEach(function(result) {
                        newResults[result.state].push(result)
                    }), []),
                    resultsOrder = ["problems", "warnings", "success"];
                return Object.keys(newResults).sort(function(a, b) {
                    return resultsOrder.indexOf(a) - resultsOrder.indexOf(b)
                }).forEach(function(key) {
                    newResults[key].length && (key = _this.getResultsHtml(key, newResults[key]), resultsElements.push(key))
                }), resultsElements
            },
            setIconBadge: function() {
                function getIssue(state) {
                    return allResults.find(function(result) {
                        return state === result.state
                    })
                }
                var state = "success",
                    allResults = this.model.get("results");
                getIssue("problems") ? state = "problems" : getIssue("warnings") && (state = "warnings"), this.$navbarIcon.removeClass(this.currentBadge), this.currentBadge = "vc_ui-badge--" + state, this.$navbarIcon.addClass(this.currentBadge)
            },
            getResultsHtml: function(type, data) {
                var type = $('<div class="vc_ui-seo-results-section"><strong>' + window.i18nLocale[type] + '</strong><ul class="vc_ui-seo-results-list"></ul></div>'),
                    $resultList = type.find("ul");
                return $.each(data, function(i, val) {
                    val = $('<li class="vc_ui-seo-results-list-item vc_ui-seo-results-list-item--' + val.state + '">' + val.title + ": " + val.description + "</li>");
                    $resultList.append(val)
                }), type
            },
            getContentWrapper: function() {
                return "admin_frontend_editor" === window.vc_mode ? vc.$frame.contents().find(".wpb-content-wrapper") : $("#wpbakery_content")
            }
        }), vc.events.on("app.render", function() {
            setTimeout(function() {
                vc.seo_analysis_view = new vc.SeoAnalysisView({
                    el: "#vc_ui-seo-analysis",
                    model: vc.seo_storage
                })
            }, 1e3)
        })
    }(window.jQuery), window._.isUndefined(window.vc) && (window.vc = {}),
    function(vc, _, $) {
        "use strict";
        window.vc_toTitleCase = function(str) {
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
            })
        }, window.vc_convert_column_size = function(width) {
            var width = width ? width.split("/") : [1, 1],
                range = _.range(1, 13),
                num = !_.isUndefined(width[0]) && 0 <= _.indexOf(range, parseInt(width[0], 10)) && parseInt(width[0], 10),
                range = !_.isUndefined(width[1]) && 0 <= _.indexOf(range, parseInt(width[1], 10)) && parseInt(width[1], 10);
            return !1 !== num && !1 !== range ? "vc_col-sm-" + 12 * num / range : "vc_col-sm-12"
        }, window.vc_convert_column_span_size = function(width) {
            return "span12" === (width = width.replace(/^vc_/, "")) ? "1/1" : "span11" === width ? "11/12" : "span10" === width ? "5/6" : "span9" === width ? "3/4" : "span8" === width ? "2/3" : "span7" === width ? "7/12" : "span6" === width ? "1/2" : "span5" === width ? "5/12" : "span4" === width ? "1/3" : "span3" === width ? "1/4" : "span2" === width ? "1/6" : "span1" === width && "1/12"
        }, window.vc_get_column_mask = function(cells) {
            var i, sp, numbers_sum, columns = cells.split("_"),
                cells = columns.length;
            for (i in numbers_sum = 0, columns) !isNaN(parseFloat(columns[i])) && isFinite(columns[i]) && (sp = columns[i].match(/(\d{1,2})(\d{1,2})/), numbers_sum = _.reduce(sp.slice(1), function(memo, num) {
                return memo + parseInt(num, 10)
            }, numbers_sum));
            return cells + "" + numbers_sum
        }, window.VCS4 = function() {
            return (65536 * (1 + Math.random()) | 0).toString(16).substring(1)
        }, window.vc_guid = function() {
            return window.VCS4() + window.VCS4() + "-" + window.VCS4()
        }, window.vc_button_param_target_callback = function() {
            var $link_target = this.$content.find("[name=target]").parents('[data-vc-ui-element="panel-shortcode-param"]:first'),
                $link_field = $(".wpb-edit-form [name=href]"),
                key_up_callback = _.debounce(function() {
                    var val = $(this).val();
                    0 < val.length && "http://" !== val && "https://" !== val ? $link_target.show() : $link_target.hide()
                }, 300);
            $link_field.on("keyup", key_up_callback).trigger("keyup")
        }, window.vc_cta_button_param_target_callback = function() {
            var $link_target = this.$content.find("[name=target]").parents('[data-vc-ui-element="panel-shortcode-param"]:first'),
                $link_field = $(".wpb-edit-form [name=href]"),
                key_up_callback = _.debounce(function() {
                    var val = $(this).val();
                    0 < val.length && "http://" !== val && "https://" !== val ? $link_target.show() : $link_target.hide()
                }, 300);
            $link_field.on("keyup", key_up_callback).trigger("keyup")
        }, window.vc_grid_exclude_dependency_callback = function() {
            var exclude_obj = $(".wpb_vc_param_value[name=exclude]", this.$content).data("vc-param-object");
            if (!exclude_obj) return !1;
            var post_type_object = $('select.wpb_vc_param_value[name="post_type"]', this.$content),
                val = post_type_object.val();
            exclude_obj.source_data = function(request, response) {
                return {
                    query: {
                        query: val,
                        term: request.term
                    }
                }
            }, exclude_obj.source_data_val = val, post_type_object.on("change", function() {
                val = $(this).val(), exclude_obj.source_data_val != val && (exclude_obj.source_data = function(request, response) {
                    return {
                        query: {
                            query: val,
                            term: request.term
                        }
                    }
                }, exclude_obj.$el.data("uiAutocomplete").destroy(), exclude_obj.$sortable_wrapper.find(".vc_data").remove(), exclude_obj.render(), exclude_obj.source_data_val = val)
            })
        }, window.vcGridFilterExcludeCallBack = function() {
            var $filterBy = $(".wpb_vc_param_value[name=filter_source]", this.$content),
                defaultValue = $filterBy.val(),
                autocomplete = $(".wpb_vc_param_value[name=exclude_filter]", this.$content).data("vc-param-object");
            if (void 0 === autocomplete) return !1;
            $filterBy.on("change", function() {
                var $this = $(this);
                defaultValue !== $this.val() && autocomplete.clearValue(), autocomplete.source_data = function() {
                    return {
                        vc_filter_by: $this.val()
                    }
                }
            }).trigger("change")
        }, window.vcGridTaxonomiesCallBack = function() {
            var $filterByPostType = $(".wpb_vc_param_value[name=post_type]", this.$content),
                defaultValue = $filterByPostType.val(),
                autocomplete = $(".wpb_vc_param_value[name=taxonomies]", this.$content).data("vc-param-object");
            if (void 0 === autocomplete) return !1;
            $filterByPostType.on("change", function() {
                var $this = $(this);
                defaultValue !== $this.val() && autocomplete.clearValue(), autocomplete.source_data = function() {
                    return {
                        vc_filter_post_type: $filterByPostType.val()
                    }
                }
            }).trigger("change")
        }, window.vcChartCustomColorDependency = function() {
            var $masterEl = $(".wpb_vc_param_value[name=style]", this.$content),
                $content = this.$content;
            $masterEl.on("change", function() {
                var masterValue = $(this).val();
                $content.toggleClass("vc_chart-edit-form-custom-color", "custom" === masterValue)
            }), $masterEl.trigger("change")
        }, window.vc_wpnop = function(html) {
            var blocklist1, blocklist, preserve_linebreaks, preserve_br, preserve;
            return html = void 0 !== html ? html + "" : "", window.switchEditors && void 0 !== window.switchEditors.pre_wpautop ? (html = window.switchEditors.pre_wpautop(html)).replace(/<p>(<!--(?:.*)-->)<\/p>/g, "$1") : html ? (blocklist1 = (blocklist = "blockquote|ul|ol|li|dl|dt|dd|table|thead|tbody|tfoot|tr|th|td|h[1-6]|fieldset|figure") + "|div|p", blocklist = blocklist + "|pre", preserve_br = preserve_linebreaks = !1, preserve = [], -1 !== (html = -1 === html.indexOf("<script") && -1 === html.indexOf("<style") ? html : html.replace(/<(script|style)[^>]*>[\s\S]*?<\/\1>/g, function(match) {
                return preserve.push(match), "<wp-preserve>"
            })).indexOf("<pre") && (preserve_linebreaks = !0, html = html.replace(/<pre[^>]*>[\s\S]+?<\/pre>/g, function(a) {
                return (a = (a = a.replace(/<br ?\/?>(\r\n|\n)?/g, "<wp-line-break>")).replace(/<\/?p( [^>]*)?>(\r\n|\n)?/g, "<wp-line-break>")).replace(/\r?\n/g, "<wp-line-break>")
            })), -1 !== html.indexOf("[caption") && (preserve_br = !0, html = html.replace(/\[caption[\s\S]+?\[\/caption\]/g, function(a) {
                return a.replace(/<br([^>]*)>/g, "<wp-temp-br$1>").replace(/[\r\n\t]+/, "")
            })), html = (html = (html = (html = (html = -1 !== (html = -1 !== (html = -1 !== (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = (html = html.replace(new RegExp("\\s*</(" + blocklist1 + ")>\\s*", "g"), "</$1>\n")).replace(new RegExp("\\s*<((?:" + blocklist1 + ")(?: [^>]*)?)>", "g"), "\n<$1>")).replace(/(<p [^>]+>.*?)<\/p>/g, "$1</p#>")).replace(/<div( [^>]*)?>\s*<p>/gi, "<div$1>\n\n")).replace(/\s*<p>/gi, "")).replace(/\s*<\/p>\s*/gi, "\n\n")).replace(/\n[\s\u00a0]+\n/g, "\n\n")).replace(/(\s*)<br ?\/?>\s*/gi, function(match, space) {
                return space && -1 !== space.indexOf("\n") ? "\n\n" : "\n"
            })).replace(/\s*<div/g, "\n<div")).replace(/<\/div>\s*/g, "</div>\n")).replace(/\s*\[caption([^\[]+)\[\/caption\]\s*/gi, "\n\n[caption$1[/caption]\n\n")).replace(/caption\]\n\n+\[caption/g, "caption]\n\n[caption")).replace(new RegExp("\\s*<((?:" + blocklist + ")(?: [^>]*)?)\\s*>", "g"), "\n<$1>")).replace(new RegExp("\\s*</(" + blocklist + ")>\\s*", "g"), "</$1>\n")).replace(/<((li|dt|dd)[^>]*)>/g, " \t<$1>")).indexOf("<option") ? (html = html.replace(/\s*<option/g, "\n<option")).replace(/\s*<\/select>/g, "\n</select>") : html).indexOf("<hr") ? html.replace(/\s*<hr( [^>]*)?>\s*/g, "\n\n<hr$1>\n\n") : html).indexOf("<object") ? html.replace(/<object[\s\S]+?<\/object>/g, function(a) {
                return a.replace(/[\r\n]+/g, "")
            }) : html).replace(/<\/p#>/g, "</p>\n")).replace(/\s*(<p [^>]+>[\s\S]*?<\/p>)/g, "\n$1")).replace(/^\s+/, "")).replace(/[\s\u00a0]+$/, ""), preserve_linebreaks && (html = html.replace(/<wp-line-break>/g, "\n")), preserve_br && (html = html.replace(/<wp-temp-br([^>]*)>/g, "<br$1>")), preserve.length ? html.replace(/<wp-preserve>/g, function() {
                return preserve.shift()
            }) : html) : ""
        }, window.vc_wpautop = function(text) {
            var preserve_linebreaks, preserve_br, blocklist;
            return text = void 0 !== text ? text + "" : "", (text = window.switchEditors && void 0 !== window.switchEditors.wpautop ? window.switchEditors.wpautop(text) : (preserve_br = preserve_linebreaks = !1, blocklist = "table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary", -1 === (text = (text = -1 !== (text = text.replace(/\r\n|\r/g, "\n")).indexOf("<object") ? text.replace(/<object[\s\S]+?<\/object>/g, function(a) {
                return a.replace(/\n+/g, "")
            }) : text).replace(/<[^<>]+>/g, function(a) {
                return a.replace(/[\n\t ]+/g, " ")
            })).indexOf("<pre") && -1 === text.indexOf("<script") || (preserve_linebreaks = !0, text = text.replace(/<(pre|script)[^>]*>[\s\S]*?<\/\1>/g, function(a) {
                return a.replace(/\n/g, "<wp-line-break>")
            })), -1 !== (text = -1 !== text.indexOf("<figcaption") ? (text = text.replace(/\s*(<figcaption[^>]*>)/g, "$1")).replace(/<\/figcaption>\s*/g, "</figcaption>") : text).indexOf("[caption") && (preserve_br = !0, text = text.replace(/\[caption[\s\S]+?\[\/caption\]/g, function(a) {
                return (a = (a = a.replace(/<br([^>]*)>/g, "<wp-temp-br$1>")).replace(/<[^<>]+>/g, function(b) {
                    return b.replace(/[\n\t ]+/, " ")
                })).replace(/\s*\n\s*/g, "<wp-temp-br />")
            })), text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text = (text += "\n\n").replace(/<br \/>\s*<br \/>/gi, "\n\n")).replace(new RegExp("(<(?:" + blocklist + ")(?: [^>]*)?>)", "gi"), "\n\n$1")).replace(new RegExp("(</(?:" + blocklist + ")>)", "gi"), "$1\n\n")).replace(/<hr( [^>]*)?>/gi, "<hr$1>\n\n")).replace(/\s*<option/gi, "<option")).replace(/<\/option>\s*/gi, "</option>")).replace(/\n\s*\n+/g, "\n\n")).replace(/([\s\S]+?)\n\n/g, "<p>$1</p>\n")).replace(/<p>\s*?<\/p>/gi, "")).replace(new RegExp("<p>\\s*(</?(?:" + blocklist + ")(?: [^>]*)?>)\\s*</p>", "gi"), "$1")).replace(/<p>(<li.+?)<\/p>/gi, "$1")).replace(/<p>\s*<blockquote([^>]*)>/gi, "<blockquote$1><p>")).replace(/<\/blockquote>\s*<\/p>/gi, "</p></blockquote>")).replace(new RegExp("<p>\\s*(</?(?:" + blocklist + ")(?: [^>]*)?>)", "gi"), "$1")).replace(new RegExp("(</?(?:" + blocklist + ")(?: [^>]*)?>)\\s*</p>", "gi"), "$1")).replace(/(<br[^>]*>)\s*\n/gi, "$1")).replace(/\s*\n/g, "<br />\n")).replace(new RegExp("(</?(?:" + blocklist + ")[^>]*>)\\s*<br />", "gi"), "$1")).replace(/<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)/gi, "$1")).replace(/(?:<p>|<br ?\/?>)*\s*\[caption([^\[]+)\[\/caption\]\s*(?:<\/p>|<br ?\/?>)*/gi, "[caption$1[/caption]")).replace(/(<(?:div|th|td|form|fieldset|dd)[^>]*>)(.*?)<\/p>/g, function(a, b, c) {
                return c.match(/<p( [^>]*)?>/) ? a : b + "<p>" + c + "</p>"
            }), preserve_linebreaks && (text = text.replace(/<wp-line-break>/g, "\n")), preserve_br ? text.replace(/<wp-temp-br([^>]*)>/g, "<br$1>") : text)).replace(/<p>(<!--(?:.*)-->)<\/p>/g, "$1")
        }, window.vc_regexp_shortcode = _.memoize(function() {
            return RegExp("\\[(\\[?)([\\w|-]+\\b)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)")
        }), window.vcAddShortcodeDefaultParams = function(model) {
            var params = model.get("params"),
                preset = model.get("preset"),
                params = _.extend({}, vc.getDefaults(model.get("shortcode")), params);
            preset && window.vc_all_presets[preset] && (params = window.vc_all_presets[preset], void 0 !== vc.frame_window) && window.vc_all_presets[preset].css && vc.frame_window.vc_iframe.setCustomShortcodeCss(window.vc_all_presets[preset].css), model.set({
                params: params
            }, {
                silent: !0
            })
        }, window.vc_globalHashCode = function(obj) {
            return (obj = "string" != typeof obj ? JSON.stringify(obj) : obj).length ? obj.split("").reduce(function(a, b) {
                return (a = (a << 5) - a + b.charCodeAt(0)) & a
            }, 0) : 0
        }, vc.memoizeWrapper = function(func, resolver) {
            var cache = {};
            return function() {
                var key = resolver ? resolver.apply(this, arguments) : arguments[0];
                return _.hasOwnProperty.call(cache, key) || (cache[key] = func.apply(this, arguments)), _.isObject(cache[key]) ? window.jQuery.fn.extend(!0, {}, cache[key]) : cache[key]
            }
        }, window.vcChartParamAfterAddCallback = function($elem, action) {
            if ("new" !== action && "clone" !== action || $elem.find(".vc_control.column_toggle").click(), "new" === action) {
                for (var random, exclude = ["white", "black"], $select = $elem.find("[name=values_color]"), $options = $select.find("option"), i = 0;;) {
                    if (100 < i++) break;
                    if (random = Math.floor(Math.random() * $options.length), -1 === window.jQuery.inArray($options.eq(random).val(), exclude)) {
                        $options.eq(random).prop("selected", !0), $select.trigger("change");
                        break
                    }
                }
                action = ["#5472d2", "#00c1cf", "#fe6c61", "#8d6dc4", "#4cadc9", "#cec2ab", "#50485b", "#75d69c", "#f7be68", "#5aa1e3", "#6dab3c", "#f4524d", "#f79468", "#b97ebb", "#ebebeb", "#f7f7f7", "#0088cc", "#58b9da", "#6ab165", "#ff9900", "#ff675b", "#555555"], random = Math.floor(Math.random() * action.length), $elem.find("[name=values_custom_color]").val(action[random]).trigger("change")
            }
        }, vc.events.on("shortcodes:vc_row:add:param:name:parallax shortcodes:vc_row:update:param:name:parallax", function(model, value) {
            value && (value = model.get("params")) && value.css && (value.css = value.css.replace(/(background(\-position)?\s*\:\s*[\S]+(\s*[^\!\s]+)?)[\s*\!important]*/g, "$1"), model.set("params", value, {
                silent: !0
            }))
        }), vc.events.on("shortcodes:vc_single_image:sync shortcodes:vc_single_image:add", function(model) {
            var params = model.get("params");
            params.link && !params.onclick && (params.onclick = "custom_link", model.save({
                params: params
            }))
        }), window.vcEscapeHtml = function(text) {
            var map = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#039;"
            };
            return null == text ? "" : text.replace(/[&<>"']/g, function(m) {
                return map[m]
            })
        }, window.vc_slugify = function(text) {
            return text.toLowerCase().replace(/[^\w ]+/g, "").replace(/ +/g, "-")
        }
    }(window.vc, window._, window.jQuery), window.jQuery.expr.pseudos.containsi = function(a, i, m) {
        return 0 <= window.jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())
    }, _.isUndefined(window.vc) && (window.vc = {}), window.vc.filters = {
        templates: []
    }, window.vc.addTemplateFilter = function(callback) {
        _.isFunction(callback) && this.filters.templates.push(callback)
    },
    function($) {
        "use strict";

        function fixedEncodeURIComponent(str) {
            return encodeURIComponent(str).replace(/[!'()*]/g, escape)
        }

        function Suggester(element, options) {
            this.el = element, this.$el = $(this.el), this.$el_wrap = "", this.$block = "", this.suggester = "", this.selected_items = [], this.options = _.isObject(options) ? options : {}, _.defaults(this.options, {
                css_class: "vc_suggester",
                limit: !1,
                source: {},
                predefined: [],
                locked: !1,
                select_callback: function(label, data) {},
                remove_callback: function(label, data) {},
                update_callback: function(label, data) {},
                check_locked_callback: function(el, data) {
                    return !1
                }
            }), this.init()
        }
        window.init_textarea_html = function($element) {
            var textfield_id, $content_holder, $wp_link = $("#wp-link");
            $wp_link.parent().hasClass("wp-dialog") && $wp_link.wpdialog("destroy"), textfield_id = $element.attr("id"), $content_holder = ($wp_link = $element.closest(".edit_form_line")).find(".vc_textarea_html_content");
            try {
                _.isUndefined(tinyMCEPreInit.qtInit[textfield_id]) && (window.tinyMCEPreInit.qtInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.qtInit[window.wpActiveEditor], {
                    id: textfield_id
                })), window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[window.wpActiveEditor] && (window.tinyMCEPreInit.mceInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.mceInit[window.wpActiveEditor], {
                    resize: "vertical",
                    height: 200,
                    id: textfield_id,
                    setup: function(ed) {
                        void 0 !== ed.onLoadContent && ed.onLoadContent.add(function(ed, o) {
                            var controlLoad = setTimeout(function() {
                                1 === $("#" + textfield_id).size() && ($(".vc_edit-form-tab *:input[type!=hidden]:first").focus(), clearTimeout(controlLoad))
                            }, 100)
                        }), void 0 !== ed.on ? ed.on("init", function(ed) {
                            window.wpActiveEditor = textfield_id
                        }) : ed.onInit.add(function(ed) {
                            window.wpActiveEditor = textfield_id
                        })
                    }
                }), window.tinyMCEPreInit.mceInit[textfield_id].plugins = window.tinyMCEPreInit.mceInit[textfield_id].plugins.replace(/,?wpfullscreen/, ""), window.tinyMCEPreInit.mceInit[textfield_id].wp_autoresize_on = !1), vc.edit_element_block_view && vc.edit_element_block_view.currentModelParams ? $element.val(vc_wpautop(vc.edit_element_block_view.currentModelParams[$content_holder.attr("name")] || "")) : $element.val($content_holder.val()), quicktags(window.tinyMCEPreInit.qtInit[textfield_id]), QTags._buttonsInit(), window.tinymce && (window.switchEditors && window.switchEditors.go(textfield_id, "tmce"), "4" === tinymce.majorVersion) && tinymce.execCommand("mceAddEditor", !0, textfield_id), window.wpActiveEditor = textfield_id
            } catch (e) {
                $element.data("vcTinyMceDisabled", !0).appendTo($wp_link), $("#wp-" + textfield_id + "-wrap").remove(), console && console.error && (console.error("VC: Tinymce error! Compatibility problem with other plugins."), console.error(e))
            }
        }, Color.prototype.toString = function() {
            if (this._alpha < 1) return this.toCSS("rgba", this._alpha).replace(/\s+/g, "");
            var hex = parseInt(this._color, 10).toString(16);
            if (this.error) return "";
            if (hex.length < 6)
                for (var i = 6 - hex.length - 1; 0 <= i; i--) hex = "0" + hex;
            return "#" + hex
        }, vc.loop_partial = function(template_name, key, loop, settings) {
            loop = _.isObject(loop) && !_.isUndefined(loop[key]) ? loop[key] : "";
            return vc.template($("#_vcl-" + template_name).html(), vc.templateOptions.custom)({
                name: key,
                data: loop,
                settings: settings
            })
        }, vc.loop_field_not_hidden = function(key, loop) {
            return !(_.isObject(loop[key]) && _.isBoolean(loop[key].hidden) && !0 === loop[key].hidden)
        }, vc.is_locked = function(data) {
            return _.isObject(data) && _.isBoolean(data.locked) && !0 === data.locked
        }, Suggester.prototype = {
            constructor: Suggester,
            init: function() {
                _.bindAll(this, "buildSource", "itemSelected", "labelClick", "setFocus", "resize"), this.$el.wrap('<ul class="' + this.options.css_class + '"><li class="input"/></ul>'), this.$el_wrap = this.$el.parent(), this.$block = this.$el_wrap.closest("ul").append($('<li class="clear"/>')), this.$el.on("focus", this.resize).on("blur", function() {
                    $(this).parent().width(170), $(this).val("")
                }), this.$block.on("click", this.setFocus), this.suggester = this.$el.data("suggest"), this.$el.autocomplete({
                    source: this.buildSource,
                    select: this.itemSelected,
                    minLength: 2,
                    focus: function(event, ui) {
                        return !1
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $('<li data-value="' + item.value + '">').append("<a>" + item.name + "</a>").appendTo(ul)
                }, this.$el.autocomplete("widget").addClass("vc_ui-front"), _.isArray(this.options.predefined) && _.each(this.options.predefined, function(item) {
                    this.create(item)
                }, this)
            },
            resize: function() {
                var position = this.$el_wrap.position(),
                    block_position = this.$block.position();
                this.$el_wrap.width(parseFloat(this.$block.width()) - (parseFloat(position.left) - parseFloat(block_position.left) + 4))
            },
            setFocus: function(e) {
                e.preventDefault(), $(e.target).hasClass(this.options.css_class) && this.$el.trigger("focus")
            },
            itemSelected: function(event, ui) {
                return this.$el.blur(), this.create(ui.item), this.$el.trigger("focus"), !1
            },
            create: function(item) {
                var exclude_css, index = this.selected_items.push(item) - 1,
                    remove = !0 === this.options.check_locked_callback(this.$el, item) ? "" : ' <a class="remove">&times;</a>';
                _.isUndefined(this.selected_items[index].action) && (this.selected_items[index].action = "+"), exclude_css = "-" === this.selected_items[index].action ? " exclude" : " include", (exclude_css = $('<li class="vc_suggest-label' + exclude_css + '" data-index="' + index + '" data-value="' + item.value + '"><span class="label">' + item.name + "</span>" + remove + "</li>")).insertBefore(this.$el_wrap), _.isEmpty(remove) || exclude_css.on("click", this.labelClick), this.options.select_callback(exclude_css, this.selected_items)
            },
            labelClick: function(e) {
                e.preventDefault();
                var $label = $(e.currentTarget),
                    index = parseInt($label.data("index"), 10);
                if ($(e.target).is(".remove")) return this.selected_items.splice(index, 1), this.options.remove_callback($label, this.selected_items), $label.remove(), !1;
                this.selected_items[index].action = "+" === this.selected_items[index].action ? "-" : "+", "+" === this.selected_items[index].action ? $label.removeClass("exclude").addClass("include") : $label.removeClass("include").addClass("exclude"), this.options.update_callback($label, this.selected_items)
            },
            buildSource: function(request, response) {
                this.ajax && (this.ajax.abort(), response([]), this.ajax = !1);
                var exclude = _.filter(_.map(this.selected_items, function(item) {
                    return item ? item.value : void 0
                })).join(",");
                this.ajax = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: window.ajaxurl,
                    data: {
                        action: "wpb_get_loop_suggestion",
                        field: this.suggester,
                        exclude: exclude,
                        query: request.term,
                        _vcnonce: window.vcAdminNonce
                    }
                }).done(function(data) {
                    response(data)
                })
            }
        }, $.fn.suggester = function(option) {
            return this.each(function() {
                var $this = $(this),
                    data = $this.data("suggester");
                data || $this.data("suggester", data = new Suggester(this, option)), "string" == typeof option && data[option]()
            })
        };
        var VcLoopEditorView = Backbone.View.extend({
                className: "loop_params_holder",
                events: {
                    "click input, select": "save",
                    "change input, select": "save",
                    "change :checkbox[data-input]": "updateCheckbox"
                },
                query_options: {},
                return_array: {},
                controller: "",
                initialize: function() {
                    _.bindAll(this, "save", "updateSuggestion", "suggestionLocked")
                },
                render: function(controller) {
                    var template = vc.template($("#vcl-loop-frame").html(), _.extend({}, vc.templateOptions.custom, {
                        variable: "loop"
                    }));
                    return this.controller = controller, this.$el.html(template(this.model)), this.controller.$el.append(this.$el), _.each($("[data-suggest]"), function(object) {
                        var object = $(object),
                            current_value = window.decodeURIComponent($("[data-suggest-prefill=" + object.data("suggest") + "]").val());
                        object.suggester({
                            predefined: $.parseJSON(current_value),
                            select_callback: this.updateSuggestion,
                            update_callback: this.updateSuggestion,
                            remove_callback: this.updateSuggestion,
                            check_locked_callback: this.suggestionLocked
                        })
                    }, this), this.save(), this
                },
                show: function() {
                    this.$el.slideDown()
                },
                save: function(e) {
                    this.return_array = {}, _.each(this.model, function(value, key) {
                        value = this.getValue(key, value);
                        _.isString(value) && !_.isEmpty(value) && (this.return_array[key] = value)
                    }, this), this.controller.setInputValue(this.return_array)
                },
                getValue: function(key) {
                    return $("[name=" + key + "]", this.$el).val()
                },
                hide: function() {
                    this.$el.slideUp()
                },
                toggle: function() {
                    this.$el.is(":animated") || this.$el.slideToggle()
                },
                updateCheckbox: function(e) {
                    var e = $(e.currentTarget).data("input"),
                        $input = $("[data-name=" + e + "]", this.$el),
                        value = [];
                    $("[data-input=" + e + "]:checked").each(function() {
                        value.push($(this).val())
                    }), $input.val(value), this.save()
                },
                updateSuggestion: function($elem, data) {
                    $elem = $elem.closest("[data-block=suggestion]"), data = _.reduce(data, function(memo, label) {
                        return _.isEmpty(label) ? "" : memo + (_.isEmpty(memo) ? "" : ",") + ("-" === label.action ? "-" : "") + label.value
                    }, "").trim();
                    $elem.find("[data-suggest-value]").val(data).trigger("change")
                },
                suggestionLocked: function($elem, data) {
                    data = data.value, $elem = $elem.closest("[data-block=suggestion]").find("[data-suggest-value]").data("suggest-value");
                    return this.controller.settings && this.controller.settings[$elem] && _.isBoolean(this.controller.settings[$elem].locked) && 1 == this.controller.settings[$elem].locked && _.isString(this.controller.settings[$elem].value) && 0 <= _.indexOf(this.controller.settings[$elem].value.replace("-", "").split(/\,/), "" + data)
                }
            }),
            VcLoop = Backbone.View.extend({
                events: {
                    "click .vc_loop-build": "showEditor"
                },
                initialize: function() {
                    _.bindAll(this, "createEditor"), this.$input = $(".wpb_vc_param_value", this.$el), this.$button = this.$el.find(".vc_loop-build"), this.data = this.$input.val(), this.settings = $.parseJSON(window.decodeURIComponent(this.$button.data("settings")))
                },
                render: function() {
                    return this
                },
                showEditor: function(e) {
                    if (e.preventDefault(), _.isObject(this.loop_editor_view)) return this.loop_editor_view.toggle(), !1;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: window.ajaxurl,
                        data: {
                            action: "wpb_get_loop_settings",
                            value: this.data,
                            settings: this.settings,
                            post_id: vc_post_id,
                            _vcnonce: window.vcAdminNonce
                        }
                    }).done(this.createEditor)
                },
                createEditor: function(data) {
                    this.loop_editor_view = new VcLoopEditorView({
                        model: _.isEmpty(data) ? {} : data
                    }), this.loop_editor_view.render(this).show()
                },
                setInputValue: function(value) {
                    this.$input.val(_.map(value, function(value, key) {
                        return key + ":" + value
                    }).join("|"))
                }
            }),
            VcOptionsField = Backbone.View.extend({
                events: {
                    "click .vc_options-edit": "showEditor",
                    "click .vc_close-button": "showEditor",
                    "click input, select": "save",
                    "change input, select": "save",
                    "keyup input": "save"
                },
                data: {},
                fields: {},
                initialize: function() {
                    this.$button = this.$el.find(".vc_options-edit"), this.$form = this.$el.find(".vc_options-fields"), this.$input = this.$el.find(".wpb_vc_param_value"), this.settings = this.$form.data("settings"), this.parseData(), this.render()
                },
                render: function() {
                    var html = "";
                    return _.each(this.settings, function(field) {
                        _.isUndefined(this.data[field.name]) ? _.isUndefined(field.value) || (field.value = field.value.toString().split(","), this.data[field.name] = field.value) : field.value = this.data[field.name], this.fields[field.name] = field;
                        var $field = $("#vcl-options-field-" + field.type);
                        $field.is("script") && ($field = vc.template($field.html(), vc.templateOptions.custom), html += $field(_.extend({}, {
                            name: "",
                            label: "",
                            value: [],
                            options: "",
                            description: ""
                        }, field)))
                    }, this), this.$form.html(html + this.$form.html()), this
                },
                parseData: function() {
                    _.each(this.$input.val().split("|"), function(data) {
                        var name;
                        data.match(/\:/) && (name = (data = data.split(":"))[0], data = data[1], this.data[name] = _.map(data.split(","), function(v) {
                            return window.decodeURIComponent(v)
                        }))
                    }, this)
                },
                saveData: function() {
                    var data_string = _.map(this.data, function(value, key) {
                        return key + ":" + _.map(value, function(v) {
                            return window.encodeURIComponent(v)
                        }).join(",")
                    }).join("|");
                    this.$input.val(data_string)
                },
                showEditor: function() {
                    this.$form.slideToggle()
                },
                save: function(e) {
                    var value, e = $(e.currentTarget);
                    e.is(":checkbox") ? (value = [], this.$el.find("input[name=" + e.attr("name") + "]").each(function() {
                        this.checked && value.push($(this).val())
                    }), this.data[e.attr("name")] = value) : this.data[e.attr("name")] = [e.val()], this.saveData()
                }
            });

        function VcSortedList(element, settings) {
            this.el = element, this.$el = $(this.el), this.$data_field = this.$el.find(".wpb_vc_param_value"), this.$toolbar = this.$el.find(".vc_sorted-list-toolbar"), this.$current_control = this.$el.find(".vc_sorted-list-container"), _.defaults(this.options, {}), this.init()
        }
        VcSortedList.prototype = {
            constructor: VcSortedList,
            init: function() {
                _.bindAll(this, "controlEvent", "save"), this.$toolbar.on("change", "input", this.controlEvent);

                function iteratee(item) {
                    return window.decodeURIComponent(item)
                }
                var i, selected_data = this.$data_field.val().split(",");
                for (i in selected_data) {
                    var control_settings = selected_data[i].split("|"),
                        $control = !(!control_settings.length || !control_settings[0].length) && this.$toolbar.find("[data-element=" + decodeURIComponent(control_settings[0]) + "]");
                    !1 !== $control && $control.is("input") && ($control.prop("checked", !0), this.createControl({
                        value: $control.val(),
                        label: $control.parent().text(),
                        sub: $control.data("subcontrol"),
                        sub_value: _.map(control_settings.slice(1), iteratee)
                    }))
                }
                this.$current_control.sortable({
                    stop: this.save
                }).on("change", "select", this.save)
            },
            createControl: function(data) {
                var sub_control = "",
                    selected_sub_value = _.isUndefined(data.sub_value) ? [] : data.sub_value;
                _.isArray(data.sub) && _.each(data.sub, function(sub, index) {
                    sub_control += " <select>", _.each(sub, function(item) {
                        sub_control += '<option value="' + item[0] + '"' + (_.isString(selected_sub_value[index]) && selected_sub_value[index] === item[0] ? ' selected="true"' : "") + ">" + item[1] + "</option>"
                    }), sub_control += "</select>"
                }, this), this.$current_control.append('<li class="vc_control-' + data.value + '" data-name="' + data.value + '">' + data.label + sub_control + "</li>")
            },
            controlEvent: function(e) {
                e = $(e.currentTarget);
                e[0].checked ? this.createControl({
                    value: e.val(),
                    label: e.parent().text(),
                    sub: e.data("subcontrol")
                }) : this.$current_control.find(".vc_control-" + e.val()).remove(), this.save()
            },
            save: function() {
                var string_value = _.map(this.$current_control.find("[data-name]"), function(element) {
                    var return_string = encodeURIComponent($(element).data("name"));
                    return $(element).find("select").each(function() {
                        var $sub_control = $(this);
                        $sub_control.is("select") && "" !== $sub_control.val() && (return_string += "|" + encodeURIComponent($sub_control.val()))
                    }), return_string
                }).join(",");
                this.$data_field.val(string_value)
            }
        }, $.fn.VcSortedList = function(option) {
            return this.each(function() {
                var $this = $(this),
                    data = $this.data("vc_sorted_list");
                _.isObject(option);
                data || $this.data("vc_sorted_list", data = new VcSortedList(this)), "string" == typeof option && data[option]()
            })
        };
        var GoogleFonts = Backbone.View.extend({
                preview_el: ".vc_google_fonts_form_field-preview-container > span",
                font_family_dropdown_el: ".vc_google_fonts_form_field-font_family-container > select",
                font_style_dropdown_el: ".vc_google_fonts_form_field-font_style-container > select",
                font_style_dropdown_el_container: ".vc_google_fonts_form_field-font_style-container",
                status_el: ".vc_google_fonts_form_field-status-container > span",
                events: {
                    "change .vc_google_fonts_form_field-font_family-container > select": "fontFamilyDropdownChange",
                    "change .vc_google_fonts_form_field-font_style-container > select": "fontStyleDropdownChange"
                },
                initialize: function(attr) {
                    _.bindAll(this, "previewElementInactive", "previewElementActive", "previewElementLoading"), this.$preview_el = $(this.preview_el, this.$el), this.$font_family_dropdown_el = $(this.font_family_dropdown_el, this.$el), this.$font_style_dropdown_el = $(this.font_style_dropdown_el, this.$el), this.$font_style_dropdown_el_container = $(this.font_style_dropdown_el_container, this.$el), this.$status_el = $(this.status_el, this.$el), this.fontFamilyDropdownRender()
                },
                render: function() {
                    return this
                },
                previewElementRender: function() {
                    return this.$preview_el.css({
                        "font-family": this.font_family,
                        "font-style": this.font_style,
                        "font-weight": this.font_weight
                    }), this
                },
                previewElementInactive: function() {
                    this.$status_el.text(window.i18nLocale.gfonts_loading_google_font_failed || "Loading google font failed.").css("color", "#FF0000")
                },
                previewElementActive: function() {
                    this.$preview_el.text("Grumpy wizards make toxic brew for the evil Queen and Jack.").css("color", "inherit"), this.fontStyleDropdownRender()
                },
                previewElementLoading: function() {
                    this.$preview_el.text(window.i18nLocale.gfonts_loading_google_font || "Loading Font...")
                },
                fontFamilyDropdownRender: function() {
                    return this.fontFamilyDropdownChange(), this
                },
                fontFamilyDropdownChange: function() {
                    var $font_family_selected = this.$font_family_dropdown_el.find(":selected");
                    return this.font_family_url = $font_family_selected.val(), this.font_family = $font_family_selected.attr("data[font_family]"), this.font_types = $font_family_selected.attr("data[font_types]"), this.$font_style_dropdown_el_container.parent().hide(), this.font_family_url && 0 < this.font_family_url.length && WebFont.load({
                        google: {
                            families: [this.font_family_url]
                        },
                        inactive: this.previewElementInactive,
                        active: this.previewElementActive,
                        loading: this.previewElementLoading
                    }), this
                },
                fontStyleDropdownRender: function() {
                    var str_inner, str_arr = this.font_types.split(","),
                        oel = "",
                        default_f_style = this.$font_family_dropdown_el.attr("default[font_style]");
                    for (str_inner in str_arr) var str_arr_inner = str_arr[str_inner].split(":"),
                        sel = "",
                        oel = oel + "<option " + (sel = _.isString(default_f_style) && 0 < default_f_style.length && str_arr[str_inner] == default_f_style ? "selected" : sel) + ' value="' + str_arr[str_inner] + '" data[font_weight]="' + str_arr_inner[1] + '" data[font_style]="' + str_arr_inner[2] + '" class="' + str_arr_inner[2] + "_" + str_arr_inner[1] + '" >' + str_arr_inner[0] + "</option>";
                    return this.$font_style_dropdown_el.html(oel), this.$font_style_dropdown_el_container.parent().show(), this.fontStyleDropdownChange(), this
                },
                fontStyleDropdownChange: function() {
                    var $font_style_selected = this.$font_style_dropdown_el.find(":selected");
                    return this.font_weight = $font_style_selected.attr("data[font_weight]"), this.font_style = $font_style_selected.attr("data[font_style]"), this.previewElementRender(), this
                }
            }),
            VC_AutoComplete = Backbone.View.extend({
                min_length: 2,
                delay: 500,
                auto_focus: !0,
                ajax_url: window.ajaxurl,
                source_data: function() {
                    return {}
                },
                replace_values_on_select: !1,
                initialize: function(params) {
                    _.bindAll(this, "sortableChange", "resize", "labelRemoveHook", "updateItems", "sortableCreate", "sortableUpdate", "source", "select", "labelRemoveClick", "createBox", "focus", "response", "change", "close", "open", "create", "search", "_renderItem", "_renderMenu", "_renderItemData", "_resizeMenu"), params = $.extend({
                        min_length: this.min_length,
                        delay: this.delay,
                        auto_focus: this.auto_focus,
                        replace_values_on_select: this.replace_values_on_select
                    }, params), this.options = params, this.param_name = this.options.param_name, this.$el = this.options.$el, this.$el_wrap = this.$el.parent(), this.$sortable_wrapper = this.$el_wrap.parent(), this.$input_param = this.options.$param_input, this.selected_items = [], this.isMultiple = !1, this.render()
                },
                resize: function() {
                    var position = this.$el_wrap.position(),
                        block_position = this.$block.position();
                    this.$el.autocomplete("widget").width(parseFloat(this.$block.width()) - (parseFloat(position.left) - parseFloat(block_position.left) + 4) + 11)
                },
                enableMultiple: function() {
                    this.isMultiple = !0, this.$el.show(), this.$el.trigger("focus")
                },
                enableSortable: function() {
                    this.sortable = this.$sortable_wrapper.sortable({
                        items: ".vc_data",
                        axis: "y",
                        change: this.sortableChange,
                        create: this.sortableCreate,
                        update: this.sortableUpdate
                    })
                },
                updateItems: function() {
                    this.selected_items.length ? this.$input_param.val(this.getSelectedItems().join(", ")) : this.$input_param.val("")
                },
                sortableChange: function(event, ui) {},
                itemsCreate: function() {
                    var sel_items = [];
                    this.$block.find(".vc_data").each(function(key, item) {
                        sel_items.push({
                            label: item.dataset.label,
                            value: item.dataset.value
                        })
                    }), this.selected_items = sel_items
                },
                sortableCreate: function(event, ui) {},
                sortableUpdate: function(event, ui) {
                    var elems = this.$sortable_wrapper.sortable("toArray", {
                            attribute: "data-index"
                        }),
                        items = [],
                        index = (_.each(elems, function(index) {
                            items.push(this.selected_items[index])
                        }, this), 0);
                    $("li.vc_data", this.$sortable_wrapper).each(function() {
                        $(this).attr("data-index", index++)
                    }), this.selected_items = items, this.updateItems()
                },
                getWidget: function() {
                    return this.$el.autocomplete("widget")
                },
                render: function() {
                    var that;
                    return this.$el.on("focus", this.resize), this.data = this.$el.autocomplete({
                        source: this.source,
                        minLength: this.options.min_length,
                        delay: this.options.delay,
                        autoFocus: this.options.auto_focus,
                        select: this.select,
                        focus: this.focus,
                        response: this.response,
                        change: this.change,
                        close: this.close,
                        open: this.open,
                        create: this.create,
                        search: this.search
                    }), this.data.data("ui-autocomplete")._renderItem = this._renderItem, this.data.data("ui-autocomplete")._renderMenu = this._renderMenu, this.data.data("ui-autocomplete")._resizeMenu = this._resizeMenu, 0 < this.$input_param.val().length && (this.isMultiple ? this.$el.trigger("focus") : this.$el.hide(), $(".vc_autocomplete-label.vc_data", (that = this).$sortable_wrapper).each(function() {
                        that.labelRemoveHook($(this))
                    })), this.getWidget().addClass("vc_ui-front").addClass("vc_ui-auotocomplete"), this.$block = this.$el_wrap.closest("ul").append($('<li class="clear"/>')), this.itemsCreate(), this
                },
                close: function(event, ui) {
                    this.selected && this.options.no_hide && (this.getWidget().show(), this.selected++, 2 < this.selected) && (this.selected = void 0)
                },
                open: function(event, ui) {
                    var widget = this.getWidget().menu(),
                        widget_position = widget.position();
                    widget.css("left", widget_position.left - 6), widget.css("top", widget_position.top + 2)
                },
                focus: function(event, ui) {
                    if (!this.options.replace_values_on_select) return event.preventDefault(), !1
                },
                create: function(event, ui) {},
                change: function(event, ui) {},
                response: function(event, ui) {},
                search: function(event, ui) {},
                select: function(event, ui) {
                    var $li_el, $prev_el, $next_el;
                    return this.selected = 1, ui.item && (this.options.unique_values && ($li_el = this.getWidget().data("uiMenu").active, this.options.groups && ($prev_el = $li_el.prev(), $next_el = $li_el.next(), $prev_el.hasClass("vc_autocomplete-group")) && !$next_el.hasClass("vc_autocomplete-item") && $prev_el.remove(), $li_el.remove(), $("li.ui-menu-item", this.getWidget()).length || (this.selected = void 0)), this.createBox(ui.item), this.isMultiple ? this.$el.trigger("focus") : this.$el.hide()), !1
                },
                createBox: function(item) {
                    var index = this.selected_items.push(item) - 1;
                    this.updateItems(), (index = $('<li class="vc_autocomplete-label vc_data" data-index="' + index + '" data-value="' + item.value + '" data-label="' + item.label + '"><span class="vc_autocomplete-label"><a>' + item.label + '</a></span><a class="vc_autocomplete-remove">&times;</a></li>')).insertBefore(this.$el_wrap), this.labelRemoveHook(index)
                },
                labelRemoveHook: function($label) {
                    this.$el.blur(), this.$el.val(""), $label.on("click", this.labelRemoveClick)
                },
                labelRemoveClick: function(e, ui) {
                    e.preventDefault();
                    var $label = $(e.currentTarget);
                    if ($(e.target).is(".vc_autocomplete-remove")) return this.selected_items.splice($label.index(), 1), $label.remove(), this.updateItems(), this.$el.show(), !1
                },
                getSelectedItems: function() {
                    var results;
                    return !!this.selected_items.length && (results = [], _.each(this.selected_items, function(item) {
                        results.push(item.value)
                    }), results)
                },
                _renderMenu: function(ul, items) {
                    var that = this,
                        group = null;
                    this.options.groups && items.sort(function(a, b) {
                        return a.group > b.group
                    }), $.each(items, function(index, item) {
                        that.options.groups && item.group != group && (group = item.group, ul.append("<li class='ui-autocomplete-group vc_autocomplete-group' aria-label='" + group + "'>" + group + "</li>")), that._renderItemData(ul, item)
                    })
                },
                _renderItem: function(ul, item) {
                    return $('<li data-value="' + item.value + '" class="vc_autocomplete-item">').append("<a>" + item.label + "</a>").appendTo(ul)
                },
                _renderItemData: function(ul, item) {
                    return this._renderItem(ul, item).data("ui-autocomplete-item", item)
                },
                _resizeMenu: function() {},
                clearValue: function() {
                    this.selected_items = [], this.updateItems(), $(".vc_autocomplete-label.vc_data", this.$sortable_wrapper).remove()
                },
                source: function(request, response) {
                    var that = this;
                    this.options.values && 0 < this.options.values.length ? this.options.unique_values ? response($.ui.autocomplete.filter(_.difference(this.options.values, this.selected_items), request.term)) : response($.ui.autocomplete.filter(this.options.values, request.term)) : $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: this.ajax_url,
                        data: $.extend({
                            action: "vc_get_autocomplete_suggestion",
                            shortcode: vc.active_panel.model.get("shortcode"),
                            param: this.param_name,
                            query: request.term,
                            _vcnonce: window.vcAdminNonce
                        }, this.source_data(request, response))
                    }).done(function(data) {
                        that.options.unique_values ? response(_.filter(data, function(obj) {
                            return !_.findWhere(that.selected_items, obj)
                        })) : response(data)
                    })
                }
            }),
            Vc_ParamInitializer = Backbone.View.extend({
                $content: {},
                initialize: function() {
                    _.bindAll(this, "content"), this.$content = this.$el, this.model = vc.active_panel.model
                },
                setContent: function($el) {
                    this.$content = $el
                },
                content: function() {
                    return this.$content
                },
                render: function() {
                    var self = this;
                    return $('[data-vc-ui-element="panel-shortcode-param"]', this.content()).each(function() {
                        var _this = $(this),
                            param = _this.data("param_settings");
                        vc.atts.init.call(self, param, _this), _this.data("vcInitParam", !0)
                    }), this
                }
            }),
            VC_ParamGroup = Backbone.View.extend({
                options: {
                    max_items: 0,
                    sortable: !0,
                    deletable: !0,
                    collapsible: !0
                },
                items: 0,
                $ul: !1,
                initializer: {},
                mappedParams: {},
                adminLabelParams: [],
                groupParamName: "",
                events: {
                    "click > .edit_form_line > .vc_param_group-list > .vc_param_group-add_content": "addNew"
                },
                initialize: function(data) {
                    var $elParam, settings, self;
                    this.$ul = this.$el.find("> .edit_form_line > .vc_param_group-list"), $elParam = $("> .wpb_vc_row", this.$ul), this.initializer = new Vc_ParamInitializer({
                        el: this.$el
                    }), this.model = vc.active_panel.model, settings = this.$ul.data("settings"), this.mappedParams = {}, this.adminLabelParams = [], this.options = _.defaults({}, _.isObject(data.settings) ? data.settings : {}, settings, this.options), this.groupParamName = this.options.param.param_name, _.isObject(this.options.param) && _.isArray(this.options.param.params) && _.each(this.options.param.params, function(param) {
                        var elemName = this.groupParamName + "_" + param.param_name;
                        this.mappedParams[elemName] = param, _.isObject(param) && !0 === param.admin_label && this.adminLabelParams.push(elemName)
                    }, this), this.items = 0, self = this, $elParam.length && $elParam.each(function() {
                        $elParam.data("vc-param-group-param", new VC_ParamGroup_Param({
                            el: $(this),
                            parent: self
                        })), self.items++, self.afterAdd($(this), "init")
                    }), this.options.sortable && this.$ul.sortable({
                        handle: ".vc_control.column_move",
                        items: "> .wpb_vc_row:not(vc_param_group-add_content-wrapper)",
                        placeholder: "vc_placeholder"
                    })
                },
                addNew: function(ev) {
                    var fn;
                    ev.preventDefault(), this.addAllowed() && (void 0 === this.options.param.callbacks || void 0 === this.options.param.callbacks.before_add || "function" != typeof(fn = window[this.options.param.callbacks.before_add]) || fn()) && ((fn = $(JSON.parse(this.$ul.next(".vc_param_group-template").html()))).removeClass("vc_param_group-add_content-wrapper"), fn.insertBefore(ev.currentTarget), fn.show(), this.initializer.setContent(fn.find("> .wpb_element_wrapper")), this.initializer.render(), this.items++, fn.data("vc-param-group-param", new VC_ParamGroup_Param({
                        el: fn,
                        parent: this
                    })), this.afterAdd(fn, "new"), vc.events.trigger("vc-param-group-add-new", ev, fn, this))
                },
                addAllowed: function() {
                    return 0 < this.options.max_items && this.items + 1 <= this.options.max_items || this.options.max_items <= 0
                },
                afterAdd: function($newEl, action) {
                    var fn;
                    this.addAllowed() || (this.$ul.find("> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_clone").hide(), this.$ul.find("> .vc_param_group-add_content").hide()), this.options.sortable || this.$ul.find("> .wpb_vc_row > .vc_param_group-controls > .vc_control.column_move").hide(), this.options.deletable || this.$ul.find("> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_delete").hide(), this.options.collapsible || this.$ul.find("> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_toggle").hide(), void 0 !== this.options.param.callbacks && void 0 !== this.options.param.callbacks.after_add && "function" == typeof(fn = window[this.options.param.callbacks.after_add]) && fn($newEl, action)
                },
                afterDelete: function() {
                    var fn;
                    this.addAllowed() && (this.$ul.find("> .wpb_vc_row > .vc_param_group-controls > .vc_row_edit_clone_delete > .vc_control.column_clone").show(), this.$ul.find("> .vc_param_group-add_content").show()), void 0 !== this.options.param.callbacks && void 0 !== this.options.param.callbacks.after_delete && "function" == typeof(fn = window[this.options.param.callbacks.after_delete]) && fn()
                }
            }),
            VC_ParamGroup_Param = Backbone.View.extend({
                dependentElements: !1,
                mappedParams: !1,
                groupParamName: "",
                adminLabelParams: [],
                events: {
                    "click > .vc_controls > .vc_row_edit_clone_delete > .vc_control.column_toggle": "toggle",
                    "click > .vc_controls > .vc_row_edit_clone_delete > .vc_control.column_delete": "deleteParam",
                    "click > .vc_controls > .vc_row_edit_clone_delete > .vc_control.column_clone": "clone"
                },
                initialize: function(options) {
                    this.options = options, this.$content = this.options.parent.$ul, this.model = vc.active_panel.model, this.mappedParams = this.options.parent.mappedParams, this.groupParamName = this.options.parent.groupParamName, this.adminLabelParams = this.options.parent.adminLabelParams, this.dependentElements = {}, _.bindAll(this, "hookDependent"), this.initializeDependency(), _.bindAll(this, "hookAdminLabel"), this.initializeAdminLabels()
                },
                initializeAdminLabels: function() {
                    for (var callback = this.hookAdminLabel, f = function() {
                            var $field = $(this);
                            $field.data("vc_admin_labels") || ($field.data("vc_admin_labels", !0), $field.on("keyup change", callback), callback({
                                currentTarget: this
                            }))
                        }, i = 0; i < this.adminLabelParams.length; i++) $("[name=" + this.adminLabelParams[i] + "].wpb_vc_param_value", this.$el).each(f)
                },
                hookAdminLabel: function(e) {
                    for (var labelName = "", labelValue = "", labels = [], $parent = ($field = $(e.currentTarget)).closest(".vc_param_group-wrapper"), e = $field.closest(".vc_param").find(".vc_param-group-admin-labels"), i = 0; i < this.adminLabelParams.length; i++) {
                        var $field, elemName = this.adminLabelParams[i],
                            $paramWrapper = ($field = $parent.find("[name=" + elemName + "]")).closest('[data-vc-ui-element="panel-shortcode-param"]');
                        void 0 !== this.mappedParams[elemName] && (labelName = this.mappedParams[elemName].heading), labelValue = $field.is("select") ? $field.find("option:selected").text() : !$field.is("input:checkbox") || $field[0].checked ? $field.val() : "", elemName = {
                            type: $paramWrapper.data("param_type"),
                            param_name: $paramWrapper.data("param_name")
                        }, "" !== (labelValue = _.isObject(vc.atts[elemName.type]) && _.isFunction(vc.atts[elemName.type].render) ? vc.atts[elemName.type].render.call(this, elemName, labelValue) : labelValue) && labels.push("<label>" + _.escape(labelName) + "</label>: " + _.escape(labelValue))
                    }
                    e.html(labels.join(", ")).toggleClass("vc_hidden-label", !labels.length)
                },
                initializeDependency: function() {
                    var callDependencies = {};
                    _.each(this.mappedParams, function(param, name) {
                        var $masters, $slave;
                        _.isObject(param) && _.isObject(param.dependency) && _.isString(param.dependency.element) && ($masters = $("[name=" + this.groupParamName + "_" + param.dependency.element + "].wpb_vc_param_value", this.$el), ($slave = $("[name=" + name + "].wpb_vc_param_value", this.$el)).length) && _.each($masters, function(master) {
                            var master = $(master),
                                masterName = master.attr("name"),
                                rules = param.dependency;
                            _.isArray(this.dependentElements[masterName]) || (this.dependentElements[masterName] = []), this.dependentElements[masterName].push($slave), master.data("dependentSet") || (master.attr("data-dependent-set", "true"), master.on("keyup change", this.hookDependent)), callDependencies[masterName] || (callDependencies[masterName] = master), _.isString(rules.callback) && window[rules.callback].call(this)
                        }, this)
                    }, this), _.each(callDependencies, function(obj) {
                        this.hookDependent({
                            currentTarget: obj
                        })
                    }, this)
                },
                hookDependent: function(e) {
                    var e = $(e.currentTarget),
                        $masterContainer = e.closest(".vc_column"),
                        dependentElements = this.dependentElements[e.attr("name")],
                        masterValue = e.is(":checkbox") ? _.map(this.$el.find("[name=" + e.attr("name") + "].wpb_vc_param_value:checked"), function(element) {
                            return $(element).val()
                        }) : e.val(),
                        isMasterEmpty = e.is(":checkbox") ? !this.$el.find("[name=" + e.attr("name") + "].wpb_vc_param_value:checked").length : !masterValue.length;
                    return $masterContainer.hasClass("vc_dependent-hidden") ? _.each(dependentElements, function($element) {
                        var event = $.Event("change");
                        event.extra_type = "vcHookDependedParamGroup", $element.closest(".vc_column").addClass("vc_dependent-hidden"), $element.trigger(event)
                    }) : _.each(dependentElements, function($element) {
                        var paramName = $element.attr("name"),
                            paramName = _.isObject(this.mappedParams[paramName]) && _.isObject(this.mappedParams[paramName].dependency) ? this.mappedParams[paramName].dependency : {},
                            $paramBlock = $element.closest(".vc_column");
                        _.isBoolean(paramName.not_empty) && !0 === paramName.not_empty && !isMasterEmpty || _.isBoolean(paramName.is_empty) && !0 === paramName.is_empty && isMasterEmpty || paramName.value && _.intersection(_.isArray(paramName.value) ? paramName.value : [paramName.value], _.isArray(masterValue) ? masterValue : [masterValue]).length || paramName.value_not_equal_to && !_.intersection(_.isArray(paramName.value_not_equal_to) ? paramName.value_not_equal_to : [paramName.value_not_equal_to], _.isArray(masterValue) ? masterValue : [masterValue]).length ? $paramBlock.removeClass("vc_dependent-hidden") : $paramBlock.addClass("vc_dependent-hidden"), (paramName = $.Event("change")).extra_type = "vcHookDependedParamGroup", $element.trigger(paramName)
                    }, this), this
                },
                deleteParam: function(ev) {
                    ev && ev.preventDefault && ev.preventDefault(), !0 === confirm(window.i18nLocale.press_ok_to_delete_section) && (this.options.parent.items--, this.options.parent.afterDelete(), this.$el.remove(), this.unbind(), this.remove())
                },
                content: function() {
                    return this.$content
                },
                clone: function(ev) {
                    var $content, value;
                    ev.preventDefault(), this.options.parent.addAllowed() && (ev = this.options.parent.$ul.data("settings"), $content = this.$content, this.$content = this.$el, value = vc.atts.param_group.parseOne.call(this, ev), $.ajax({
                        type: "POST",
                        url: window.ajaxurl,
                        data: {
                            action: "vc_param_group_clone",
                            param: fixedEncodeURIComponent(JSON.stringify(ev)),
                            shortcode: vc.active_panel.model.get("shortcode"),
                            value: value,
                            vc_inline: !0,
                            _vcnonce: window.vcAdminNonce
                        },
                        dataType: "json",
                        context: this
                    }).done(function(response) {
                        response = response.data || "", response = $(response);
                        response.insertAfter(this.$el), this.$content = $content, this.options.parent.initializer.$content = $("> .wpb_element_wrapper", response), this.options.parent.initializer.render(), response.data("vc-param-group-param", new VC_ParamGroup_Param({
                            el: response,
                            parent: this.options.parent
                        })), this.options.parent.items++, this.options.parent.afterAdd(response, "clone")
                    }))
                },
                toggle: function(ev) {
                    ev.preventDefault();
                    ev = this.$el;
                    ev.find("> .wpb_element_wrapper").slideToggle(), ev.toggleClass("vc_param_group-collapsed").siblings(":not(.vc_param_group-collapsed)").addClass("vc_param_group-collapsed").find("> .wpb_element_wrapper").slideUp()
                }
            }),
            getControlsHTML = (window.i18nLocale, vc.edit_form_callbacks = [], vc.atts = {
                parse: function(param) {
                    var $field = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]"),
                        $param = $field.closest('[data-vc-ui-element="panel-shortcode-param"]'),
                        $param = _.isUndefined(vc.atts[param.type]) || _.isUndefined(vc.atts[param.type].parse) ? $field.length ? $field.val() : null : $param.data("vcInitParam") ? vc.atts[param.type].parse.call(this, param) : ($param = this.model.get("params"), _.isUndefined($param[param.param_name]) ? $field.length ? $field.val() : null : $param[param.param_name]);
                    return void 0 !== $field.data("js-function") && void 0 !== window[$field.data("js-function")] && (0, window[$field.data("js-function")])(this.$el, this, param), $param
                },
                parseFrame: function(param) {
                    return vc.atts.parse.call(this, param)
                },
                init: function(param, $field) {
                    _.isUndefined(vc.atts[param.type]) || _.isUndefined(vc.atts[param.type].init) || vc.atts[param.type].init.call(this, param, $field)
                }
            }, vc.atts.textarea_html = {
                parse: function(param) {
                    var _window = this.window(),
                        param = this.content().find(".textarea_html." + param.param_name);
                    try {
                        _window.tinyMCE && _.isArray(_window.tinyMCE.editors) && _.each(_window.tinyMCE.editors, function(_editor) {
                            "wpb_tinymce_content" === _editor.id && _editor.save()
                        })
                    } catch (err) {
                        window.console && window.console.warn && window.console.warn("textarea_html atts parse error", err)
                    }
                    return param.val()
                },
                render: function(param, value) {
                    return _.isUndefined(value) ? value : vc_wpautop(value)
                }
            }, vc.atts.textarea_safe = {
                parse: function(param) {
                    param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").val();
                    return param.match(/"|(http)/) ? "#E-8_" + base64_encode(rawurlencode(param)) : param
                },
                render: function(param, value) {
                    return value && value.match(/^#E\-8_/) ? $("<div/>").text(rawurldecode(base64_decode(value.replace(/^#E\-8_/, "")))).html() : value
                }
            }, vc.atts.checkbox = {
                parse: function(param) {
                    var arr = [],
                        newValue = "";
                    return $("input[name=" + param.param_name + "]", this.content()).each(function() {
                        var self = $(this);
                        this.checked && arr.push(self.attr("value"))
                    }), newValue = 0 < arr.length ? arr.join(",") : newValue
                },
                defaults: function(param) {
                    return ""
                }
            }, vc.atts.el_id = {
                clone: function(clonedModel, paramValue, paramSettings) {
                    var shortcodeParams = clonedModel.get("params");
                    _.isUndefined(paramSettings) || _.isUndefined(paramSettings.settings) || _.isUndefined(paramSettings.settings.auto_generate) || !0 !== paramSettings.settings.auto_generate ? shortcodeParams[paramSettings.param_name] = "" : shortcodeParams[paramSettings.param_name] = Date.now() + "-" + vc_guid(), clonedModel.set({
                        params: shortcodeParams
                    }, {
                        silent: !0
                    })
                },
                create: function(shortcodeModel, paramValue, paramSettings) {
                    if (shortcodeModel.get("cloned")) return vc.atts.el_id.clone(shortcodeModel, paramValue, paramSettings);
                    !_.isEmpty(paramValue) || _.isUndefined(paramSettings) || _.isUndefined(paramSettings.settings) || _.isUndefined(paramSettings.settings.auto_generate) || 1 != paramSettings.settings.auto_generate || ((paramValue = shortcodeModel.get("params"))[paramSettings.param_name] = Date.now() + "-" + vc_guid(), shortcodeModel.set({
                        params: paramValue
                    }, {
                        silent: !0
                    }))
                }
            }, vc.events.on("shortcodes:add:param:type:el_id", vc.atts.el_id.create), vc.atts.posttypes = {
                parse: function(param) {
                    var posstypes_arr = [],
                        new_value = "";
                    return $("input[name=" + param.param_name + "]", this.content()).each(function() {
                        var self = $(this);
                        this.checked && posstypes_arr.push(self.attr("value"))
                    }), new_value = 0 < posstypes_arr.length ? posstypes_arr.join(",") : new_value
                }
            }, vc.atts.taxonomies = {
                parse: function(param) {
                    var posstypes_arr = [],
                        new_value = "";
                    return $("input[name=" + param.param_name + "]", this.content()).each(function() {
                        var self = $(this);
                        this.checked && posstypes_arr.push(self.attr("value"))
                    }), new_value = 0 < posstypes_arr.length ? posstypes_arr.join(",") : new_value
                }
            }, vc.atts.exploded_textarea = {
                parse: function(param) {
                    return this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").val().replace(/\n/g, ",")
                }
            }, vc.atts.exploded_textarea_safe = {
                parse: function(param) {
                    param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").val();
                    return (param = param.replace(/\n/g, ",")).match(/"|(http)/) ? "#E-8_" + base64_encode(rawurlencode(param)) : param
                },
                render: function(param, value) {
                    return value && value.match(/^#E\-8_/) ? $("<div/>").text(rawurldecode(base64_decode(value.replace(/^#E\-8_/, "")))).html() : value
                }
            }, vc.atts.textarea_raw_html = {
                parse: function(param) {
                    param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").val();
                    return base64_encode(rawurlencode(param))
                },
                render: function(param, value) {
                    return value ? $("<div/>").text(rawurldecode(base64_decode(value.trim()))).html() : ""
                }
            }, vc.atts.dropdown = {
                render: function(param, value) {
                    return value
                },
                init: function(param, $field) {
                    $(".wpb_vc_param_value.dropdown", $field).on("change", function() {
                        var $this = $(this),
                            $options = $this.find(":selected"),
                            prev_option_class = $this.data("option"),
                            $options = $options.length ? $options.attr("class").replace(/\s/g, "_") : "";
                        $options = $options.replace("#", "hash-"), void 0 !== prev_option_class && $this.removeClass(prev_option_class), void 0 !== $options && ($this.data("option", $options), $this.addClass($options))
                    })
                },
                defaults: function(param) {
                    var values;
                    return _.isArray(param.value) || _.isString(param.value) ? _.isArray(param.value) ? (values = param.value[0], _.isArray(values) && values.length ? values[0] : values) : "" : (values = _.values(param.value)[0]).label ? values.value : values
                }
            }, vc.atts.attach_images = {
                parse: function(param) {
                    var $field = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]"),
                        thumbnails_html = "";
                    return $field.parent().find("li.added").each(function() {
                        thumbnails_html += '<li><img src="' + $(this).find("img").attr("src") + '" alt=""></li>'
                    }), $("[data-model-id=" + this.model.id + "]").data("field-" + param.param_name + "-attach-images", thumbnails_html), $field.length ? $field.val() : null
                },
                render: function(param, value) {
                    var $thumbnails = this.$el.find(".attachment-thumbnails[data-name=" + param.param_name + "]");
                    return "external_link" === this.model.getParam("source") && (value = this.model.getParam("custom_srcs")), _.isEmpty(value) ? (this.$el.removeData("field-" + param.param_name + "-attach-images"), vc.atts.attach_images.updateImages($thumbnails, "")) : $.ajax({
                        type: "POST",
                        url: window.ajaxurl,
                        data: {
                            action: "wpb_gallery_html",
                            content: value,
                            _vcnonce: window.vcAdminNonce
                        },
                        dataType: "json",
                        context: this
                    }).done(function(response) {
                        response = response.data;
                        vc.atts.attach_images.updateImages($thumbnails, response)
                    }), value
                },
                updateImages: function($thumbnails, thumbnails_html) {
                    $thumbnails.html(thumbnails_html), thumbnails_html.length ? $thumbnails.removeClass("image-exists").next().addClass("image-exists") : $thumbnails.addClass("image-exists").next().removeClass("image-exists")
                }
            }, vc.atts.href = {
                parse: function(param) {
                    var param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]"),
                        val = "";
                    return val = param.length && "http://" !== param.val() ? param.val() : val
                }
            }, vc.atts.attach_image = {
                parse: function(param) {
                    var $field = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]"),
                        image_src = "";
                    return $field.parent().find("li.added").length && (image_src = $field.parent().find("li.added img").attr("src")), $("[data-model-id=" + this.model.id + "]").data("field-" + param.param_name + "-attach-image", image_src), $field.length ? $field.val() : null
                },
                render: function(param, value) {
                    var $model = $("[data-model-id=" + this.model.id + "]"),
                        image_src = $model.data("field-" + param.param_name + "-attach-image"),
                        $thumbnail = this.$el.find(".attachment-thumbnail[data-name=" + param.param_name + "]"),
                        image_data = {
                            image_src: "",
                            image_alt: ""
                        };
                    return "image" === param.param_name && ("external_link" === this.model.getParam("source") ? (image_data.image_src = this.model.getParam("custom_src"), vc.atts.attach_image.updateImage($thumbnail, image_data)) : _.isEmpty(value) && "featured_image" !== this.model.getParam("source") ? _.isUndefined(image_src) || ($model.removeData("field-" + param.param_name + "-attach-image"), image_data.image_src = image_src, vc.atts.attach_image.updateImage($thumbnail, image_data)) : $.ajax({
                        type: "POST",
                        url: window.ajaxurl,
                        data: {
                            action: "wpb_single_image_data",
                            content: value,
                            params: this.model.get("params"),
                            post_id: vc_post_id,
                            _vcnonce: window.vcAdminNonce
                        },
                        dataType: "json",
                        context: this
                    }).done(function(response) {
                        var image_exists;
                        response.success && (image_exists = response.data.image_src.length || "featured_image" === this.model.getParam("source"), vc.atts.attach_image.updateImage($thumbnail, response.data, image_exists))
                    })), value
                },
                updateImage: function($thumbnail, image_data, image_exists) {
                    var image_src = image_data.image_src,
                        image_data = image_data.image_alt;
                    $thumbnail.length && ((image_exists = void 0 === image_exists ? !1 : image_exists) || !_.isEmpty(image_src) ? ($thumbnail.attr("src", image_src), $thumbnail.attr("alt", image_data), (_.isEmpty(image_src) ? ($thumbnail.hide(), $thumbnail.next().removeClass("image-exists")) : ($thumbnail.show(), $thumbnail.next().addClass("image-exists"))).next().addClass("image-exists")) : $thumbnail.attr("src", "").hide().next().removeClass("image-exists").next().removeClass("image-exists"))
                }
            }, vc.atts.google_fonts = {
                parse: function(param) {
                    var param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").parent(),
                        options = {};
                    return options.font_family = param.find(".vc_google_fonts_form_field-font_family-select > option:selected").val(), options.font_style = param.find(".vc_google_fonts_form_field-font_style-select > option:selected").val(), param = _.map(options, function(value, key) {
                        if (_.isString(value) && 0 < value.length) return key + ":" + encodeURIComponent(value)
                    }), $.grep(param, function(value) {
                        return _.isString(value) && 0 < value.length
                    }).join("|")
                },
                init: function(param, $field) {
                    var $g_fonts = $field;
                    $g_fonts.length && ("undefined" != typeof WebFont ? $field.data("vc-param-object", new GoogleFonts({
                        el: $g_fonts
                    })) : $g_fonts.find("> .edit_form_line").html(window.i18nLocale.gfonts_unable_to_load_google_fonts || "Unable to load Google Fonts"))
                }
            }, vc.atts.font_container = {
                parse: function(param) {
                    var param = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").parent(),
                        options = {};
                    return options.tag = param.find(".vc_font_container_form_field-tag-select > option:selected").val(), options.font_size = param.find(".vc_font_container_form_field-font_size-input").val(), options.text_align = param.find(".vc_font_container_form_field-text_align-select > option:selected").val(), options.font_family = param.find(".vc_font_container_form_field-font_family-select > option:selected").val(), options.color = param.find(".vc_font_container_form_field-color-input").val(), options.line_height = param.find(".vc_font_container_form_field-line_height-input").val(), options.font_style_italic = param.find(".vc_font_container_form_field-font_style-checkbox.italic").prop("checked") ? "1" : "", options.font_style_bold = param.find(".vc_font_container_form_field-font_style-checkbox.bold").prop("checked") ? "1" : "", param = _.map(options, function(value, key) {
                        if (_.isString(value) && 0 < value.length) return key + ":" + encodeURIComponent(value)
                    }), $.grep(param, function(value) {
                        return _.isString(value) && 0 < value.length
                    }).join("|")
                },
                init: function(param, $field) {
                    vc.atts.colorpicker.init.call(this, param, $field)
                }
            }, vc.atts.param_group = {
                parse: function(param) {
                    var $content = this.content(),
                        $list = $content.find('.wpb_el_type_param_group[data-vc-ui-element="panel-shortcode-param"][data-vc-shortcode-param-name="' + param.param_name + '"]').find("> .edit_form_line > .vc_param_group-list"),
                        param = vc.atts.param_group.extractValues.call(this, param, $('>.wpb_vc_row:not(".vc_param_group-add_content-wrapper")', $list));
                    return this.$content = $content, encodeURIComponent(JSON.stringify(param))
                },
                extractValues: function(param, $el) {
                    var data = [],
                        self = this;
                    return $el.each(function() {
                        var innerData = {};
                        self.$content = $(this), _.each(param.params, function(par) {
                            var value, par = $.extend({}, par),
                                innerParamName = par.param_name;
                            par.param_name = param.param_name + "_" + innerParamName, ((value = vc.atts.parse.call(self, par)).length || par.save_always) && (innerData[innerParamName] = value)
                        }), data.push(innerData)
                    }), data
                },
                parseOne: function(param) {
                    var $content = this.content(),
                        param = vc.atts.param_group.extractValues.call(this, param, $content);
                    return this.$content = $content, fixedEncodeURIComponent(JSON.stringify(param))
                },
                init: function(param, $field) {
                    $field.data("vc-param-object", new VC_ParamGroup({
                        el: $field,
                        settings: {
                            param: param
                        }
                    }))
                }
            }, vc.atts.colorpicker = {
                init: function(param, $field) {
                    $(".vc_color-control", $field).each(function() {
                        var $alpha, $alpha_output, $control = $(this),
                            value = $control.val().replace(/\s+/g, ""),
                            alpha_val = 100;
                        value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/) && (alpha_val = 100 * parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1])), $control.wpColorPicker({
                            clear: function(event, ui) {
                                $alpha.val(100), $alpha_output.val("100%")
                            },
                            change: _.debounce(function() {
                                $(this).trigger("change")
                            }, 500)
                        }), value = $control.closest(".wp-picker-container"), $('<div class="vc_alpha-container"><label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label><input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="vc_alpha-field"></div>').appendTo(value.addClass("vc_color-picker").find(".iris-picker")), $alpha = value.find(".vc_alpha-field"), $alpha_output = value.find(".vc_alpha-container output"), $alpha.on("change keyup", function() {
                            var alpha_val = parseFloat($alpha.val()),
                                iris = $control.data("a8c-iris"),
                                color_picker = $control.data("wp-wpColorPicker");
                            $alpha_output.val($alpha.val() + "%"), iris._color._alpha = alpha_val / 100, $control.val(iris._color.toString()), color_picker.toggler.css({
                                backgroundColor: $control.val()
                            })
                        }).val(alpha_val).trigger("change")
                    })
                }
            }, vc.atts.autocomplete = {
                init: function(param, $field) {
                    $field.length && $field.each(function() {
                        var $param = $(".wpb_vc_param_value", this),
                            param_name = $param.attr("name"),
                            $el = $(".vc_auto_complete_param", this),
                            param_name = $.extend({
                                $param_input: $param,
                                param_name: param_name,
                                $el: $el
                            }, $param.data("settings")),
                            $el = new VC_AutoComplete(param_name);
                        param_name.multiple && $el.enableMultiple(), param_name.sortable && $el.enableSortable(), $param.data("vc-param-object", $el)
                    })
                }
            }, vc.atts.loop = {
                init: function(param, $field) {
                    $field.data("vc-param-object", new VcLoop({
                        el: $field
                    }))
                }
            }, vc.atts.vc_link = {
                init: function(param, $field) {
                    $(".vc_link-build", $field).on("click", function(e) {
                        e && e.preventDefault && e.preventDefault(), e && e.stopImmediatePropagation && e.stopImmediatePropagation(), e = $(this).closest(".vc_link"), $input = e.find(".wpb_vc_param_value"), $url_label = e.find(".url-label"), $title_label = e.find(".title-label"), e = $input.data("json"), $link_submit = $("#wp-link-submit"), $vc_link_submit = $('<input type="submit" name="vc_link-submit" id="vc_link-submit" class="button-primary" value="Set Link">'), $link_submit.hide(), $("#vc_link-submit").remove(), $vc_link_submit.insertBefore($link_submit), $vc_link_nofollow = $('<div class="link-target vc-link-nofollow"><label><span></span> <input type="checkbox" id="vc-link-nofollow"> Add nofollow option to link</label></div>'), $("#link-options .vc-link-nofollow").remove(), $vc_link_nofollow.insertAfter($("#link-options .link-target")), setTimeout(function() {
                            var currentHeight = $("#most-recent-results").css("top");
                            $("#most-recent-results").css("top", parseInt(currentHeight, 10) + $vc_link_nofollow.height())
                        }, 200), dialog = !window.wpLink && $.fn.wpdialog && $("#wp-link").length ? {
                            $link: !1,
                            open: function() {
                                this.$link = $("#wp-link").wpdialog({
                                    title: wpLinkL10n.title,
                                    width: 480,
                                    height: "auto",
                                    modal: !0,
                                    dialogClass: "wp-dialog",
                                    zIndex: 3e5
                                }), this.$link.addClass("vc-link-wrapper")
                            },
                            close: function() {
                                this.$link && (this.$link.wpdialog("close"), this.$link.removeClass("vc-link-wrapper"))
                            }
                        } : window.wpLink;
                        var $input, $url_label, $title_label, $link_submit, $vc_link_submit, $vc_link_nofollow, dialog, onOpen = function(e, wrap) {
                                jQuery(wrap).addClass("vc-link-wrapper");
                                var $cancelButton = $("#wp-link-cancel button");
                                $vc_link_submit.off("click.vcLink").on("click.vcLink", function(e) {
                                    e && e.preventDefault && e.preventDefault(), e && e.stopImmediatePropagation && e.stopImmediatePropagation(), (e = {}).url = ($("#wp-link-url").length ? $("#wp-link-url") : $("#url-field")).val(), e.title = ($("#wp-link-text").length ? $("#wp-link-text") : $("#link-title-field")).val();
                                    var string, $checkbox = $("#wp-link-target").length ? $("#wp-link-target") : $("#link-target-checkbox");
                                    return e.target = $checkbox[0].checked ? "_blank" : "", e.rel = $("#vc-link-nofollow")[0].checked ? "nofollow" : "", string = _.map(e, function(value, key) {
                                        if (_.isString(value) && 0 < value.length) return key + ":" + encodeURIComponent(value).trim()
                                    }).filter(function(item) {
                                        return item
                                    }).join("|"), $input.val(string).trigger("change"), $input.data("json", e), $url_label.html(e.url + e.target), $title_label.html(e.title), dialog.close("noReset"), $link_submit.show(), $vc_link_submit.off("click.vcLink"), $vc_link_submit.remove(), $cancelButton.off("click.vcLinkCancel"), window.wpLink.textarea = "", $checkbox.attr("checked", !1), $("#most-recent-results").css("top", ""), $("#vc-link-nofollow").attr("checked", !1), !1
                                }), $cancelButton.off("click").on("click.vcLinkCancel", function(e) {
                                    e && e.preventDefault && e.preventDefault(), e && e.stopImmediatePropagation && e.stopImmediatePropagation(), dialog.close("noReset"), $vc_link_submit.off("click.vcLink"), $vc_link_submit.remove(), $cancelButton.off("click.vcLinkCancel"), window.wpLink.textarea = ""
                                })
                            },
                            onClose = function(e, wrap) {
                                jQuery(wrap).removeClass("vc-link-wrapper"), jQuery(document).off("wplink-open", onOpen), jQuery(document).off("wplink-close", onClose)
                            };
                        jQuery(document).off("wplink-open", onOpen).on("wplink-open", onOpen), jQuery(document).off("wplink-close", onClose).on("wplink-close", onClose), "admin_frontend_editor" === vc_mode ? dialog.open("vc-hidden-editor") : dialog.open("content"), _.isString(e.url) && ($("#wp-link-url").length ? $("#wp-link-url") : $("#url-field")).val(e.url), _.isString(e.title) && ($("#wp-link-text").length ? $("#wp-link-text") : $("#link-title-field")).val(e.title), ($("#wp-link-target").length ? $("#wp-link-target") : $("#link-target-checkbox")).prop("checked", !_.isEmpty(e.target)), $("#vc-link-nofollow").length && $("#vc-link-nofollow").prop("checked", !_.isEmpty(e.rel))
                    })
                }
            }, vc.atts.sorted_list = {
                init: function(param, $field) {
                    $(".vc_sorted-list", $field).VcSortedList()
                }
            }, vc.atts.options = {
                init: function(param, $field) {
                    $field.data("vc-param-object", new VcOptionsField({
                        el: $field
                    }))
                }
            }, vc.atts.iconpicker = {
                change: function(param, $field) {
                    $field = $field.find(".vc-iconpicker");
                    $field.val(this.value), $field.data("vc-no-check", !0), $field.find('[value="' + this.value + '"]').attr("selected", "selected"), $field.data("vcFontIconPicker").loadIcons()
                },
                parse: function(param) {
                    return this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]").parent().find(".vc-iconpicker").val()
                },
                init: function(param, $field) {
                    var $el = $field.find(".wpb_vc_param_value"),
                        settings = $.extend({
                            iconsPerPage: 100,
                            iconDownClass: "fip-fa fa fa-arrow-down",
                            iconUpClass: "fip-fa fa fa-arrow-up",
                            iconLeftClass: "fip-fa fa fa-arrow-left",
                            iconRightClass: "fip-fa fa fa-arrow-right",
                            iconSearchClass: "fip-fa fa fa-search",
                            iconCancelClass: "fip-fa fa fa-remove",
                            iconBlockClass: "fip-fa"
                        }, $el.data("settings"));
                    $field.find(".vc-iconpicker").vcFontIconPicker(settings).on("change", function(e) {
                        var $select = $(this);
                        $select.data("vc-no-check") || $el.data("vc-no-check", !0).val(this.value).trigger("change"), $select.data("vc-no-check", !1)
                    }), $el.on("change", function(e) {
                        $el.data("vc-no-check") || vc.atts.iconpicker.change.call(this, param, $field), $el.data("vc-no-check", !1)
                    })
                }
            }, vc.atts.animation_style = {
                init: function(param, $field) {
                    var content = $field,
                        $field_input = $(".wpb_vc_param_value[name=" + param.param_name + "]", content);

                    function animation_style_test(el, x) {
                        $(el).removeClass().addClass(x + " animated").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                            $(this).removeClass().addClass("vc_param-animation-style-preview")
                        })
                    }
                    $('option[value="' + $field_input.val() + '"]', content).attr("selected", !0), $(".vc_param-animation-style-trigger", content).on("click", function(e) {
                        e.preventDefault();
                        e = $(".vc_param-animation-style", content).val();
                        "none" !== e && animation_style_test(this.parentNode, "vc_param-animation-style-preview " + e)
                    }), $(".vc_param-animation-style", content).on("change", function() {
                        var animation = $(this).val();
                        $field_input.val(animation), "none" !== animation && animation_style_test($(".vc_param-animation-style-preview", content), "vc_param-animation-style-preview " + animation)
                    })
                }
            }, vc.atts.gutenberg = {
                content: null,
                gutenbergParamObj: null,
                $frame: null,
                closeEditor: function(e) {
                    e && e.preventDefault && e.preventDefault();
                    var _this = this;
                    _.delay(function() {
                        _this.content.find(".vc_gutenberg-modal-wrapper").html(""), _this.$frame = null, _this.gutenbergParamObj = null
                    }, 100)
                },
                updateEditor: function(e) {
                    e && e.preventDefault && e.preventDefault(), this.gutenbergParamObj && this.gutenbergParamObj.updateValueFromIframe(), this.closeEditor()
                },
                init: function(param, $field) {
                    var _this = vc.atts.gutenberg,
                        $field_input = (_this.content = $field, $(".wpb_vc_param_value[name=" + param.param_name + "]", _this.content));
                    $('[data-vc-action="open"]', _this.content).on("click", function(e) {
                        e.preventDefault();
                        var e = $field_input.val(),
                            iframeURL = window.wpbGutenbergEditorUrl || "/wp-admin/post-new.php?post_type=wpb_gutenberg_param";
                        _this.gutenbergParamObj = new GutenbergParam({
                            onSetValue: function(value) {
                                $field_input.val(value)
                            },
                            onError: _this.closeEditor,
                            value: e
                        }), vc.createOverlaySpinner(), _this.content.find(".vc_gutenberg-modal-wrapper").html('<div class="wpb-gutenberg-modal"><div class="wpb-gutenberg-modal-inner"><iframe style="width: 100%;" data-vc-gutenberg-param-iframe></iframe></div></div>'), _this.$frame = _this.content.find("iframe[data-vc-gutenberg-param-iframe]"), _this.$frame.attr("src", iframeURL), _this.$frame.on("load", function() {
                            vc.removeOverlaySpinner(), _this.gutenbergParamObj && (_this.gutenbergParamObj.iframe = _this.$frame.get(0), _this.gutenbergParamObj.iframeLoaded())
                        })
                    })
                }
            }, function() {
                return '<div class="wpb-gutenberg-controls-container"><style>.wpb-gutenberg-controls-container {display: flex;justify-content: center;align-items: center;}.vc_gutenberg-modal-update-button {padding-top: 8px;padding-bottom: 8px;min-height: 10px;padding: 5px 10px;font-size: 12px;line-height: 1.5;border-radius: 3px;color: #fff;background-color: #00aef0;border-color: transparent;cursor: pointer;display: inline-block;text-decoration: none !important;}.vc_gutenberg-modal-update-button:hover {background-color: #0089bd;}.wpb-gutenberg-modal-close-button {display: inline-flex;justify-content: center;align-items: center;margin: 0 0 0 10px;background: transparent;border: 0;box-shadow: none;padding: 5px;cursor: pointer;outline: none;}.wpb-gutenberg-modal-close-button:hover .vc-c-icon-close {opacity: 1;}.vc-c-icon-close {position: relative;display: inline-flex;width: 13px;height: 13px;justify-content: center;align-items: center;transform: rotate(45deg);opacity: .65;transition: opacity .2s ease-in-out;}.vc-c-icon-close::before,.vc-c-icon-close::after {content: "";position: absolute;background: #353535;}.vc-c-icon-close::before {width: 1px;height: 100%;}.vc-c-icon-close::after {width: 100%;height: 1px;}.interface-interface-skeleton__sidebar {z-index: -1;}</style><button class="vc_gutenberg-modal-update-button">' + (window.i18nLocale.gutenbergEditorUpdateButton || "Update") + '</button><button class="wpb-gutenberg-modal-close-button"><i class="vc-composer-icon vc-c-icon-close"></i></button></div>'
            }),
            GutenbergParam = function(options) {
                return this.iframe = null, this.options = options || {}, this.value = this.options && this.options.value ? this.options.value : "", this.iframeLoaded = function() {
                    var wpData = !!this.iframe.contentWindow.wp && this.iframe.contentWindow.wp.data,
                        postId = (wpData || (localizations = (localizations = window.i18nLocale || !1) && localizations.gutenbergDoesntWorkProperly ? localizations.gutenbergDoesntWorkProperly : "Gutenberg plugin doesn't work properly. Please check Gutenberg plugin.", window.alert(localizations), this.options && this.options.onError && this.options.onError()), parseInt(this.iframe.contentWindow.document.getElementById("post_ID").value)),
                        newPost = {
                            id: postId,
                            guid: {
                                raw: "/?",
                                rendered: "/?"
                            },
                            title: {
                                raw: ""
                            },
                            content: {
                                raw: this.value,
                                rendered: this.value
                            },
                            type: "wpb_gutenberg_param",
                            slug: "",
                            status: "auto-draft",
                            link: "/?",
                            format: "standard",
                            categories: []
                        },
                        editor = wpData.dispatch("core/editor"),
                        localizations = wpData.select("core/edit-post"),
                        postTitle = this.iframe.contentWindow.document.querySelector(".editor-post-title"),
                        notice = this.iframe.contentWindow.document.querySelector(".components-notice-list"),
                        contentValue = (postTitle && postTitle.classList.add("hidden"), notice && notice.classList.add("hidden"), localizations.isPublishSidebarOpened = function() {
                            return !0
                        }, "function" == typeof editor.autosave && (editor.autosave = function() {}), this.value),
                        isEditorSetup = !1,
                        currentIframe = (wpData.subscribe(function() {
                            var currentPost = wpData.select("core/editor").getCurrentPost();
                            !isEditorSetup && currentPost && currentPost.id === postId && (isEditorSetup = !0, editor.setupEditor(newPost, {
                                content: contentValue
                            }))
                        }), this.iframe);
                    setTimeout(function() {
                        var iframe, postToolbar, controlHTML;
                        iframe = $(iframe = currentIframe).contents(), postToolbar = iframe.find(".edit-post-header-toolbar"), controlHTML = getControlsHTML(), $(controlHTML).insertAfter(postToolbar), iframe.find(".vc_gutenberg-modal-update-button, .wpb-gutenberg-modal-close-button").on("click", function() {
                            setTimeout(function() {
                                window.sessionStorage.removeItem("wp-autosave-block-editor-post-auto-draft")
                            }, "3000")
                        }), controlHTML = vc.atts.gutenberg, iframe.find(".wpb-gutenberg-modal-close-button").on("click", controlHTML.closeEditor.bind(controlHTML)), iframe.find(".vc_gutenberg-modal-update-button").on("click", controlHTML.updateEditor.bind(controlHTML))
                    }, "3000")
                }, this.updateValueFromIframe = function() {
                    var wpData;
                    this.iframe && this.iframe.contentWindow && this.iframe.contentWindow.wp && this.iframe.contentWindow.wp.data && (wpData = this.iframe.contentWindow.wp.data) && (wpData = wpData.select("core/editor").getEditedPostContent(), this.setValue(wpData))
                }, this.setValue = function(value) {
                    this.value = value, this.options.onSetValue && this.options.onSetValue(value)
                }, this
            };
        vc.atts.vc_grid_id = {
            parse: function() {
                return "vc_gid:" + Date.now() + "-" + this.model.get("id") + "-" + Math.floor(11 * Math.random())
            }
        }, vc.atts.addShortcodeIdParam = function(model) {
            var _changed = !1,
                params = model.get("params"),
                settings = vc.map[model.get("shortcode")];
            _.isArray(settings.params) && _.each(settings.params, function(p) {
                p && !_.isUndefined(p.type) && ("tab_id" === p.type && _.isEmpty(params[p.param_name]) ? (_changed = !0, params[p.param_name] = vc_guid() + "-" + Math.floor(11 * Math.random())) : "vc_grid_id" === p.type && (_changed = !0, params[p.param_name] = vc.atts.vc_grid_id.parse.call({
                    model: model
                })))
            }), _changed && model.save("params", params, {
                silent: !0
            })
        }, vc.getMapped = vc.memoizeWrapper(function(tag) {
            return vc.map[tag] || {}
        })
    }(window.jQuery),
    function($) {
        "use strict";
        vc.debug = !1, vc.map = _.isUndefined(window.vc_mapper) ? {} : window.vc_mapper, vc.roles = _.isUndefined(window.vc_roles) ? {} : window.vc_roles, vc.Storage = function() {
            this.data = {}
        }, vc.Storage.prototype = {
            url: window.ajaxurl,
            checksum: !1,
            locked: !1,
            isChanged: !1,
            create: function(model) {
                return model.id || (model.id = model.attributes.id = vc_guid()), this.data[model.id] = model.toJSON(), this.setModelRoot(model.id), this.save(), model
            },
            lock: function() {
                this.locked = !0
            },
            unlock: function() {
                this.locked = !1
            },
            setModelRoot: function(id) {
                id = this.data[id];
                _.isString(id.parent_id) && _.isObject(this.data[id.parent_id]) && (id.root_id = this.data[id.parent_id].root_id), _.isObject(this.data[id.root_id]) && (this.data[id.root_id].html = !1)
            },
            update: function(model) {
                return this.data[model.id] = model.toJSON(), this.setModelRoot(model.id), this.save(), model
            },
            destroy: function(model) {
                return _.isUndefined(this.data[model.id]) || _.isUndefined(this.data[model.id].root_id) || !_.isObject(this.data[this.data[model.id].root_id]) || (this.data[this.data[model.id].root_id].html = !1), _.isUndefined(this.data[model.id]) || delete this.data[model.id], this.save(), model
            },
            find: function(model_id) {
                return this.data[model_id]
            },
            findAll: function() {
                return this.fetch(), _.values(this.data)
            },
            findAllRootSorted: function() {
                var models = _.filter(_.values(this.data), function(model) {
                    return !1 === model.parent_id
                });
                return _.sortBy(models, function(model) {
                    return model.order
                })
            },
            escapeParam: function(value) {
                if($.type(value)!=="array")
					return _.isUndefined(value) || _.isNull(value) || !value.toString ? "" : value.toString().replace(/"/g, "``").replace(/\[/g, "`{`").replace(/\]/g, "`}`")
				else
					return value;
				return '';
            },
            unescapeParam: function(value) {
                return value = value.replace(/\`{\`/g, "[").replace(/\`}\`/g, "]").replace(/(\`{2})/g, '"'), value = vc_wpnop(value)
            },
            storageCreateShortcodeString: function(model) {
                var content, tag = model.get("shortcode"),
                    params = _.extend({}, model.get("params")),
                    paramsForString = {},
                    params = vc.getMergedParams(tag, params);
                return _.each(params, function(value, key) {
                    paramsForString[key] = this.escapeParam(value)
                }, this), params = vc.getMapped(tag), params = _.isObject(params) && (_.isBoolean(params.is_container) && !0 === params.is_container || !_.isEmpty(params.as_parent)), content = this._storageGetShortcodeContent(model), content = {
                    tag: tag,
                    attrs: paramsForString,
                    content: content,
                    type: _.isUndefined(vc.getParamSettings(tag, "content")) && !params ? "single" : ""
                }, model.trigger("stringify", model, content), wp.shortcode.string(content)
            },
            save: function() {
                var content;
                return this.locked ? this.locked = !1 : (content = _.reduce(this.findAllRootSorted(), function(memo, modelArray) {
                    modelArray = vc.shortcodes.get(modelArray);
                    return memo + this.storageCreateShortcodeString(modelArray)
                }, "", this), this.setContent(content), this.checksum = vc_globalHashCode(content), this)
            },
            _storageGetShortcodeContent: function(parent) {
                var models = _.sortBy(_.filter(this.data, function(model) {
                    return model.parent_id === parent.get("id")
                }), function(model) {
                    return model.order
                });
                return models.length ? _.reduce(models, function(memo, modelArray) {
                    modelArray = vc.shortcodes.get(modelArray);
                    return memo + this.storageCreateShortcodeString(modelArray)
                }, "", this) : (models = _.extend({}, parent.get("params")), _.isUndefined(models.content) ? "" : models.content)
            },
            getContent: function() {
                return _.isObject(window.tinymce) && tinymce.editors.content && tinymce.editors.content.save(), window.vc_wpnop($("#content").val() || "")
            },
            addUndo: _.debounce(function(content) {
                vc.undoRedoApi && vc.undoRedoApi.add(content)
            }, 100),
            setContent: function(content) {
                this.addUndo(content);
                var contentTinyMce = window.tinyMCE && window.tinyMCE.get && window.tinyMCE.get("content");
                content = vc_wpautop(content), this.isChanged || window.jQuery(window).on("beforeunload.vcSave", function() {
                    return window.i18nLocale.confirm_to_leave
                }), this.isChanged = !0, $("#content").val(content), contentTinyMce && contentTinyMce.setContent && (contentTinyMce.setContent(content), contentTinyMce.fire("change"))
            },
            parseContent: function(data, content, parent) {
                var tags = _.keys(vc.map).join("|"),
                    reg = window.wp.shortcode.regexp(tags),
                    content = content.trim().match(reg);
                return _.isNull(content) || _.each(content, function(raw) {
                    var map_settings, sub_matches = raw.match(this.regexp(tags)),
                        sub_content = sub_matches[5],
                        sub_regexp = new RegExp("^[\\s]*\\[\\[?(" + _.keys(vc.map).join("|") + ")(?![\\w-])"),
                        id = window.vc_guid(),
                        atts_raw = window.wp.shortcode.attrs(sub_matches[3]),
                        atts = {};
                    _.each(atts_raw.named, function(value, key) {
                        atts[key] = this.unescapeParam(value)
                    }, this), atts_raw = {
                        id: id,
                        shortcode: sub_matches[2],
                        order: this.order,
                        params: _.extend({}, atts),
                        parent_id: !!_.isObject(parent) && parent.id,
                        root_id: _.isObject(parent) ? parent.root_id : id
                    }, map_settings = vc.map[atts_raw.shortcode], this.order += 1, _.isArray(data) ? (data.push(atts_raw), id = data.length - 1) : data[id] = atts_raw, id == atts_raw.root_id && (data[id].html = raw), _.isString(sub_content) && sub_content.match(sub_regexp) && (_.isBoolean(map_settings.is_container) && !0 === map_settings.is_container || !_.isEmpty(map_settings.as_parent) && !1 !== map_settings.as_parent) ? data = this.parseContent(data, sub_content, data[id]) : _.isString(sub_content) && sub_content.length && "vc_row" === sub_matches[2] ? data = this.parseContent(data, '[vc_column width="1/1"][vc_column_text]' + sub_content + "[/vc_column_text][/vc_column]", data[id]) : _.isString(sub_content) && sub_content.length && "vc_column" === sub_matches[2] ? data = this.parseContent(data, "[vc_column_text]" + sub_content + "[/vc_column_text]", data[id]) : _.isString(sub_content) && (data[id].params.content = sub_content)
                }, this), data
            },
            isContentChanged: function() {
                return !1 === this.checksum || this.checksum !== vc_globalHashCode(this.getContent())
            },
            wrapData: function(content) {
                var tags = _.keys(vc.map).join("|"),
                    reg = this.regexp_split("vc_row"),
                    starts_with_shortcode = new RegExp("^\\[(\\[?)(" + tags + ")", "g"),
                    _this = this,
                    storage = {},
                    i = 0,
                    tags = (content = wp.shortcode.replace("vc_section", content, function(data) {
                        var data = {
                                attrs: data.attrs.named,
                                content: _this.wrapData(data.content)
                            },
                            hash = "vc_pseudo_section_" + ++i + "_" + VCS4() + VCS4();
                        return storage[hash] = {
                            tag: hash,
                            data: data
                        }, '[vc_row][vc_pseudo_section id="' + hash + '"][/vc_pseudo_section][/vc_row]'
                    }), _.filter(content.trim().split(reg), function(value) {
                        if (!_.isEmpty(value)) return value
                    }));
                return content = _.reduce(tags, function(mem, value) {
                    var matches_local = (value = -1 !== value.trim().indexOf("vc_pseudo_section_") || value.trim().match(starts_with_shortcode) ? value : "[vc_row][vc_column][vc_column_text]" + value + "[/vc_column_text][/vc_column][/vc_row]").match(vc_regexp_shortcode());
                    return mem + (value = !_.isArray(matches_local) || _.isUndefined(matches_local[2]) || -1 !== matches_local[2].indexOf("vc_pseudo_section_") || _.isUndefined(vc.map[matches_local[2]]) || !_.isUndefined(vc.map[matches_local[2]].is_container) && vc.map[matches_local[2]].is_container || !_.isEmpty(vc.map[matches_local[2]].as_parent) ? value : "[vc_row][vc_column]" + value + "[/vc_column][/vc_row]")
                }, ""), 0 < Object.keys(storage).length && (content = (content = content.replace(/\[vc_row\]\[vc_pseudo_section/g, "[vc_pseudo_section")).replace(/\[\/vc_pseudo_section\]\[\/vc_row\]/g, "[/vc_pseudo_section]"), content = wp.shortcode.replace("vc_pseudo_section", content, function(data) {
                    data = storage[data.attrs.named.id];
                    return wp.shortcode.string({
                        tag: "vc_section",
                        attrs: data.data.attrs,
                        content: data.data.content
                    })
                })), content
            },
            fetch: function() {
                if (!this.isContentChanged()) return this;
                this.order = 0;
                var content = this.getContent();
                this.checksum = vc_globalHashCode(content), content = this.wrapData(content), this.data = this.parseContent({}, content)
            },
            append: function(content) {
                this.data = {}, this.order = 0;
                try {
                    var current_content = this.getContent();
                    this.setContent(current_content + "" + content)
                } catch (e) {
                    window.console && window.console.warn && window.console.warn("storage.append error", e)
                }
            },
            regexp_split: _.memoize(function(tags) {
                return new RegExp("(\\[(\\[?)[" + tags + "]+(?![\\w-])[^\\]\\/]*[\\/(?!\\])[^\\]\\/]*]?(?:\\/]\\]|\\](?:[^\\[]*(?:\\[(?!\\/" + tags + "\\])[^\\[]*)*\\[\\/" + tags + "\\])?)\\]?)", "g")
            }),
            regexp: _.memoize(function(tags) {
                return new RegExp("\\[(\\[?)(" + tags + ")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)")
            })
        }, vc.storage = new vc.Storage
    }(window.jQuery),
    function() {
        "use strict";
        var store = vc.storage;
        vc.shortcode = Backbone.Model.extend({
            settings: !1,
            defaults: function() {
                var id = window.vc_guid();
                return {
                    id: id,
                    shortcode: "vc_text_block",
                    order: vc.shortcodes.getNextOrder(),
                    params: {},
                    parent_id: !1,
                    root_id: id,
                    cloned: !1,
                    html: !1,
                    view: !1
                }
            },
            initialize: function() {
                this.bind("remove", this.removeChildren, this), this.bind("remove", this.removeEvents, this)
            },
            removeEvents: function(model) {
                vc.events.triggerShortcodeEvents("destroy", model)
            },
            sync: function(method, model, options) {
                if (options && options.silent) return options.success(model);
                var response;
                switch (method) {
                    case "read":
                        response = model.id ? store.find(model) : store.findAll();
                        break;
                    case "create":
                        response = store.create(model);
                        break;
                    case "update":
                        response = store.update(model);
                        break;
                    case "delete":
                        response = store.destroy(model)
                }
                response ? options.success(response) : options.error("Record not found")
            },
            getParam: function(key) {
                return _.isObject(this.get("params")) && !_.isUndefined(this.get("params")[key]) ? this.get("params")[key] : ""
            },
            removeChildren: function(parent) {
                parent = vc.shortcodes.where({
                    parent_id: parent.id
                });
                _.each(parent, function(model) {
                    vc.storage.lock(), model.destroy(), this.removeChildren(model)
                }, this), parent.length && vc.storage.save()
            },
            setting: function(name) {
                return !1 === this.settings && (this.settings = vc.getMapped(this.get("shortcode")) || {}), this.settings[name]
            }
        }), vc.shortcodes_collection = Backbone.Collection.extend({
            model: vc.shortcode,
            last_index: 0,
            getNextOrder: function() {
                return this.last_index++
            },
            comparator: function(model) {
                return model.get("order")
            },
            initialize: function() {},
            createFromString: function(shortcodes_string, parent_model) {
                shortcodes_string = vc.storage.parseContent({}, shortcodes_string, !!_.isObject(parent_model) && parent_model.toJSON());
                _.each(_.values(shortcodes_string), function(model) {
                    vc.shortcodes.create(model)
                }, this)
            },
            sync: function(method, model, options) {
                var response;
                switch (method) {
                    case "read":
                        response = model.id ? store.find(model) : store.findAll();
                        break;
                    case "create":
                        response = store.create(model);
                        break;
                    case "update":
                        response = store.update(model);
                        break;
                    case "delete":
                        response = store.destroy(model)
                }
                response ? options.success(response) : options.error("Record not found")
            },
            stringify: function(state) {
                var models = _.sortBy(vc.shortcodes.where({
                    parent_id: !1
                }), function(model) {
                    return model.get("order")
                });
                return this.modelsToString(models, state)
            },
            singleStringify: function(id, state) {
                return this.modelsToString([vc.shortcodes.get(id)], state)
            },
            createShortcodeString: function(model, state) {
                var content, tag = model.get("shortcode"),
                    params = _.extend({}, model.get("params")),
                    paramsForString = {},
                    params = vc.getMergedParams(tag, params);
                return _.each(params, function(value, key) {
                    paramsForString[key] = vc.storage.escapeParam(value)
                }, this), params = vc.getMapped(tag), params = _.isObject(params) && (_.isBoolean(params.is_container) && !0 === params.is_container || !_.isEmpty(params.as_parent)), content = this._getShortcodeContent(model, state), content = {
                    tag: tag,
                    attrs: paramsForString,
                    content: content,
                    type: _.isUndefined(vc.getParamSettings(tag, "content")) && !params ? "single" : ""
                }, _.isUndefined(state) ? model.trigger("stringify", model, content) : model.trigger("stringify:" + state, model, content), content.remove ? "" : wp.shortcode.string(content)
            },
            modelsToString: function(models, state) {
                return _.reduce(models, function(memo, model) {
                    return memo + this.createShortcodeString(model, state)
                }, "", this)
            },
            _getShortcodeContent: function(parent, state) {
                var models = _.sortBy(vc.shortcodes.where({
                    parent_id: parent.get("id")
                }), function(model) {
                    return model.get("order")
                });
                return models.length ? _.reduce(models, function(memo, model) {
                    return memo + this.createShortcodeString(model, state)
                }, "", this) : (models = _.extend({}, parent.get("params")), _.isUndefined(models.content) ? "" : models.content)
            }
        }), vc.shortcodes = new vc.shortcodes_collection, vc.getDefaults = vc.memoizeWrapper(function(tag) {
            var defaults = {},
                tag = _.isObject(vc.map[tag]) && _.isArray(vc.map[tag].params) ? vc.map[tag].params : [];
            return _.each(tag, function(param) {
                _.isObject(param) && (_.isUndefined(param.std) ? vc.atts[param.type] && vc.atts[param.type].defaults ? defaults[param.param_name] = vc.atts[param.type].defaults(param) : _.isUndefined(param.value) || (_.isObject(param.value) ? defaults[param.param_name] = _.values(param.value)[0] : _.isArray(param.value) ? defaults[param.param_name] = param.value[0] : defaults[param.param_name] = param.value) : defaults[param.param_name] = param.std)
            }), defaults
        }), vc.getDefaultsAndDependencyMap = vc.memoizeWrapper(function(tag) {
            var dependencyMap = {},
                defaults = {},
                tag = _.isObject(vc.map[tag]) && _.isArray(vc.map[tag].params) ? vc.map[tag].params : [];
            return _.each(tag, function(param) {
                _.isObject(param) && "content" !== param.param_name && (_.isUndefined(param.std) ? _.isUndefined(param.value) || (vc.atts[param.type] && vc.atts[param.type].defaults ? defaults[param.param_name] = vc.atts[param.type].defaults(param) : _.isObject(param.value) ? defaults[param.param_name] = _.values(param.value)[0] : _.isArray(param.value) ? defaults[param.param_name] = param.value[0] : defaults[param.param_name] = param.value) : defaults[param.param_name] = param.std, _.isUndefined(param.dependency) || _.isUndefined(param.dependency.element) || (dependencyMap[param.param_name] = param.dependency))
            }), {
                defaults: defaults,
                dependencyMap: dependencyMap
            }
        }), vc.getMergedParams = function(tag, values) {
            var paramsDependencies, paramsMap = vc.getDefaultsAndDependencyMap(tag),
                outputParams = {};
            return values = _.extend({}, paramsMap.defaults, values), paramsDependencies = _.extend({}, paramsMap.dependencyMap), _.each(values, function(value, key) {
                if ("content" !== key) {
                    if (!_.isUndefined(paramsDependencies[key])) {
                        if (!_.isUndefined(paramsDependencies[paramsDependencies[key].element]) && _.isBoolean(paramsDependencies[paramsDependencies[key].element].failed) && !0 === paramsDependencies[paramsDependencies[key].element].failed) return void(paramsDependencies[key].failed = !0);
                        var dependedElement = paramsDependencies[key].element,
                            dependedValue = values[dependedElement],
                            isDependedEmpty = _.isEmpty(dependedValue),
                            dependedValueSplit = !1;
                        if ("string" == typeof dependedValue && (dependedValueSplit = values[dependedElement].split(",").map(function(i) {
                                return i.trim()
                            }).filter(function(i) {
                                return i
                            })), dependedElement = _.omit(paramsDependencies[key], "element"), _.isBoolean(dependedElement.not_empty) && !0 === dependedElement.not_empty && isDependedEmpty || _.isBoolean(dependedElement.is_empty) && !0 === dependedElement.is_empty && !isDependedEmpty || dependedElement.value && !_.intersection(_.isArray(dependedElement.value) ? dependedElement.value : [dependedElement.value], _.isArray(dependedValue) ? dependedValue : [dependedValue]).length && dependedValueSplit && dependedElement.value && !_.intersection(_.isArray(dependedElement.value) ? dependedElement.value : [dependedElement.value], _.isArray(dependedValueSplit) ? dependedValueSplit : [dependedValueSplit]).length || dependedElement.value_not_equal_to && _.intersection(_.isArray(dependedElement.value_not_equal_to) ? dependedElement.value_not_equal_to : [dependedElement.value_not_equal_to], _.isArray(dependedValue) ? dependedValue : [dependedValue]).length && dependedValueSplit && dependedElement.value_not_equal_to && _.intersection(_.isArray(dependedElement.value_not_equal_to) ? dependedElement.value_not_equal_to : [dependedElement.value_not_equal_to], _.isArray(dependedValueSplit) ? dependedValueSplit : [dependedValueSplit]).length) return void(paramsDependencies[key].failed = !0)
                    }
                    isDependedEmpty = vc.getParamSettings(tag, key), (_.isUndefined(isDependedEmpty) || !_.isUndefined(paramsMap.defaults[key]) && paramsMap.defaults[key] !== value || _.isUndefined(paramsMap.defaults[key]) && "" !== value || !_.isUndefined(isDependedEmpty.save_always) && !0 === isDependedEmpty.save_always) && (outputParams[key] = value)
                }
            }), outputParams
        }, vc.getParamSettings = vc.memoizeWrapper(function(tag, paramName) {
            tag = _.isObject(vc.map[tag]) && _.isArray(vc.map[tag].params) ? vc.map[tag].params : [];
            return _.find(tag, function(settings) {
                return _.isObject(settings) && settings.param_name === paramName
            }, this)
        }, function() {
            return arguments[0] + "," + arguments[1]
        }), vc.getParamSettingsByType = vc.memoizeWrapper(function(tag, paramType) {
            tag = _.isObject(vc.map[tag]) && _.isArray(vc.map[tag].params) ? vc.map[tag].params : [];
            return _.find(tag, function(settings) {
                return _.isObject(settings) && settings.type === paramType
            }, this)
        }, function() {
            return arguments[0] + "," + arguments[1]
        }), vc.shortcodeHasIdParam = vc.memoizeWrapper(function(tag) {
            return vc.getParamSettingsByType(tag, "el_id")
        })
    }(window.jQuery),
    function($) {
        "use strict";
        vc.clone_index = 1, vc.shortcode_view = Backbone.View.extend({
            tagName: "div",
            $content: "",
            use_default_content: !1,
            params: {},
            events: {
                "click .column_delete,.vc_control-btn-delete": "deleteShortcode",
                "click .column_add,.vc_control-btn-prepend": "addElement",
                "click .column_edit,.vc_control-btn-edit, .column_edit_trigger": "editElement",
                "click .column_clone,.vc_control-btn-clone": "clone",
                "click .column_copy,.vc_control-btn-copy": "copy",
                "click .column_paste,.vc_control-btn-paste": "paste",
                mousemove: "checkControlsPosition"
            },
            removeView: function() {
                vc.closeActivePanel(this.model), this.remove()
            },
            checkControlsPosition: function() {
                var new_position, element_height;
                this.$controls_buttons && (element_height = this.$el.height(), $(window).height() < element_height) && (40 < (new_position = $(window).scrollTop() - this.$el.offset().top + $(window).height() / 2) && new_position < element_height ? this.$controls_buttons.css("top", new_position) : element_height < new_position ? this.$controls_buttons.css("top", element_height - 40) : this.$controls_buttons.css("top", 40))
            },
            initialize: function() {
                this.model.bind("destroy", this.removeView, this), this.model.bind("change:params", this.changeShortcodeParams, this), this.model.bind("change_parent_id", this.changeShortcodeParent, this), this.createParams()
            },
            hasUserAccess: function() {
                var shortcodeTag = this.model.get("shortcode");
                return -1 < _.indexOf(["vc_row", "vc_column", "vc_row_inner", "vc_column_inner"], shortcodeTag) || !!_.every(vc.roles.current_user, function(role) {
                    return !(!_.isUndefined(vc.roles[role]) && !_.isUndefined(vc.roles[role].shortcodes) && _.isUndefined(vc.roles[role].shortcodes[shortcodeTag]))
                })
            },
            canCurrentUser: function(action) {
                var tag = this.model.get("shortcode");
                return void 0 === action || "all" === action ? vc_user_access().shortcodeAll(tag) : vc_user_access().shortcodeEdit(tag)
            },
            createParams: function() {
                var tag = this.model.get("shortcode"),
                    tag = _.isObject(vc.map[tag]) && _.isArray(vc.map[tag].params) ? vc.map[tag].params : [];
                this.model.get("params");
                this.params = {}, _.each(tag, function(param) {
                    this.params[param.param_name] = param
                }, this)
            },
            setContent: function() {
                this.$content = this.$el.find("> .wpb_element_wrapper > .vc_container_for_children, > .vc_element-wrapper > .vc_container_for_children")
            },
            setEmpty: function() {},
            unsetEmpty: function() {},
            checkIsEmpty: function() {
                this.model.get("parent_id") && vc.app.views[this.model.get("parent_id")].checkIsEmpty()
            },
            html2element: function(html) {
                var attributes = {},
                    html = vc.template(html),
                    html = $(html(this.model.toJSON()).trim());
                _.each(html.get(0).attributes, function(attr) {
                    attributes[attr.name] = attr.value
                }), this.$el.attr(attributes).html(html.html()), this.setContent(), this.renderContent()
            },
            render: function() {
                var $shortcode_template_el = $("#vc_shortcode-template-" + this.model.get("shortcode"));
                return $shortcode_template_el.is("script") && this.html2element($shortcode_template_el.html()), (this.model.view = this).$controls_buttons = this.$el.find(".vc_controls > :first"), this
            },
            renderContent: function() {
                return this.$el.attr("data-model-id", this.model.get("id")), this.$el.data("model", this.model), this
            },
            changedContent: function(view) {},
            _loadDefaults: function() {
                var tag = this.model.get("shortcode");
                !!!vc.shortcodes.where({
                    parent_id: this.model.get("id")
                }).length && !0 === this.use_default_content && _.isObject(vc.map[tag]) && _.isString(vc.map[tag].default_content) && vc.map[tag].default_content.length && (this.use_default_content = !1, vc.shortcodes.createFromString(vc.map[tag].default_content, this.model))
            },
            _callJsCallback: function() {
                var tag = this.model.get("shortcode");
                _.isObject(vc.map[tag]) && _.isObject(vc.map[tag].js_callback) && !_.isUndefined(vc.map[tag].js_callback.init) && (tag = vc.map[tag].js_callback.init, window[tag](this.$el))
            },
            ready: function(e) {
                return this._loadDefaults(), this._callJsCallback(), this.model.get("parent_id") && _.isObject(vc.app.views[this.model.get("parent_id")]) && vc.app.views[this.model.get("parent_id")].changedContent(this), _.defer(_.bind(function() {
                    vc.events.trigger("shortcodeView:ready", this), vc.events.trigger("shortcodeView:ready:" + this.model.get("shortcode"), this)
                }, this)), this
            },
            addShortcode: function(view, method) {
                var before_shortcode = _.last(vc.shortcodes.filter(function(shortcode) {
                    return shortcode.get("parent_id") === this.get("parent_id") && parseFloat(shortcode.get("order")) < parseFloat(this.get("order"))
                }, view.model));
                before_shortcode ? view.render().$el.insertAfter("[data-model-id=" + before_shortcode.id + "]") : "append" === method ? this.$content.append(view.render().el) : this.$content.prepend(view.render().el)
            },
            changeShortcodeParams: function(model) {
                var tag = model.get("shortcode"),
                    params = model.get("params"),
                    settings = vc.map[tag];
                _.defer(function() {
                    vc.events.trigger("backend.shortcodeViewChangeParams:" + tag)
                }), (_.isArray(settings.params) || _.isObject(settings.params)) && _.each(settings.params, function(param_settings) {
                    var $img, inverted_value, name = param_settings.param_name,
                        value = params[name],
                        $wrapper = this.$el.find("> .wpb_element_wrapper, > .vc_element-wrapper"),
                        label_value = value,
                        name = $wrapper.children(".admin_label_" + name);
                    _.isObject(vc.atts[param_settings.type]) && _.isFunction(vc.atts[param_settings.type].render) && (value = vc.atts[param_settings.type].render.call(this, param_settings, value)), $wrapper.children("." + param_settings.param_name).is("input,textarea,select") ? $wrapper.children("[name=" + param_settings.param_name + "]").val(value) : $wrapper.children("." + param_settings.param_name).is("iframe") ? $wrapper.children("[name=" + param_settings.param_name + "]").attr("src", value) : $wrapper.children("." + param_settings.param_name).is("img") ? ($img = $wrapper.children("[name=" + param_settings.param_name + "]"), value && value.match(/^\d+$/) ? $.ajax({
                        type: "POST",
                        url: window.ajaxurl,
                        data: {
                            action: "wpb_single_image_src",
                            content: value,
                            size: "thumbnail",
                            _vcnonce: window.vcAdminNonce
                        },
                        dataType: "html",
                        context: this
                    }).done(function(url) {
                        $img.attr("src", url)
                    }) : value && $img.attr("src", value)) : $wrapper.children("[name=" + param_settings.param_name + "]").html(value ? vc_wpautop(value) : ""), name.length && ("" === value || _.isUndefined(value) ? name.hide().addClass("hidden-label") : (_.isObject(param_settings.value) && !_.isArray(param_settings.value) && "checkbox" === param_settings.type ? (inverted_value = _.invert(param_settings.value), label_value = _.map(value.split(/[\s]*\,[\s]*/), function(val) {
                        return _.isString(inverted_value[val]) ? inverted_value[val] : val
                    }).join(", ")) : _.isObject(param_settings.value) && !_.isArray(param_settings.value) && (inverted_value = _.invert(param_settings.value), label_value = _.isString(inverted_value[value]) ? inverted_value[value] : value), name.html("<label>" + name.find("label").text() + "</label>: " + _.escape(label_value)), name.show().removeClass("hidden-label")))
                }, this), settings = vc.app.views[model.get("parent_id")], !1 !== model.get("parent_id") && _.isObject(settings) && settings.checkIsEmpty()
            },
            changeShortcodeParent: function(model) {
                var view;
                if (!1 === this.model.get("parent_id")) return model;
                model = $("[data-model-id=" + this.model.get("parent_id") + "]"), view = vc.app.views[this.model.get("parent_id")], this.$el.appendTo(model.find("> .wpb_element_wrapper > .wpb_column_container, > .vc_element-wrapper > .wpb_column_container")), view.checkIsEmpty()
            },
            deleteShortcode: function(e) {
                _.isObject(e) && e.preventDefault(), this.model.destroy()
            },
            addElement: function(e) {
                _.isObject(e) && e.preventDefault(), vc.add_element_block_view.render(this.model, !_.isObject(e) || !$(e.currentTarget).closest(".bottom-controls").hasClass("bottom-controls"))
            },
            editElement: function(e) {
                _.isObject(e) && e.preventDefault(), vc.active_panel && vc.active_panel.model && this.model && (!vc.active_panel.model || !this.model || vc.active_panel.model.get("id") === this.model.get("id")) || (vc.closeActivePanel(), vc.edit_element_block_view.render(this.model))
            },
            clone: function(e) {
                return _.isObject(e) && e.preventDefault(), vc.clone_index /= 10, this.cloneModel(this.model, this.model.get("parent_id"))
            },
            copy: function(e) {
                return _.isObject(e) && (e.preventDefault(), e.currentTarget.focus()), vc.copyShortcode(this.model)
            },
            paste: function(e) {
                return _.isObject(e) && e.preventDefault(), vc.clone_index /= 10, vc.pasteShortcode(this.model)
            },
            cloneModel: function(model, parent_id, save_order) {
                var save_order = _.isBoolean(save_order) && !0 === save_order ? model.get("order") : parseFloat(model.get("order")) + vc.clone_index,
                    params = _.extend({}, model.get("params")),
                    tag = model.get("shortcode"),
                    model_clone = vc.shortcodes.create({
                        shortcode: tag,
                        id: window.vc_guid(),
                        parent_id: parent_id,
                        order: save_order,
                        cloned: !0,
                        cloned_from: model.toJSON(),
                        params: params
                    });
                return _.each(vc.shortcodes.where({
                    parent_id: model.id
                }), function(shortcode) {
                    this.cloneModel(shortcode, model_clone.get("id"), !0)
                }, this), model_clone
            },
            remove: function() {
                this.$content && this.$content.data("uiSortable") && this.$content.sortable("destroy"), this.$content && this.$content.data("uiDroppable") && this.$content.droppable("destroy"), delete vc.app.views[this.model.id], window.vc.shortcode_view.__super__.remove.call(this)
            }
        }), vc.shortcodes.on("sync", function(collection) {
            _.isObject(collection) && !_.isEmpty(collection.models) && _.each(collection.models, function(model) {
                vc.events.triggerShortcodeEvents("sync", model)
            })
        }), vc.shortcodes.on("add", function(model) {
            _.isObject(model) && vc.events.triggerShortcodeEvents("add", model)
        })
    }(window.jQuery),
    function($) {
        vc.showMessage = function(message, type, timeout, target) {
            vc.message_timeout && ($(".vc_message").remove(), window.clearTimeout(vc.message_timeout)), type = type || "success", timeout = timeout || 1e4;
            var defaultSelector = window.vc_mode && "admin_page" === window.vc_mode ? ".metabox-composer-content" : "body",
                target = target || defaultSelector,
                $message = $('<div class="vc_message ' + type + '" style="z-index: 999;">' + message + "</div>").prependTo($(target));
            $message.fadeIn(500), vc.message_timeout = window.setTimeout(function() {
                $message.slideUp(500, function() {
                    $(this).remove()
                }), vc.message_timeout = !1
            }, timeout)
        }, window.vc_user_access && !window.vc_user_access().partAccess("unfiltered_html") && vc.showMessage(window.i18nLocale.unfiltered_html_access, "type-error", 15e3)
    }(window.jQuery),
    function($) {
        "use strict";
        vc.saved_custom_css = !1, vc.createPreLoader = function() {
            $("#vc_preloader").show()
        }, vc.removePreLoader = function() {
            $("#vc_preloader").hide()
        }, vc.createOverlaySpinner = function() {
            vc.$overlaySpinner = $("#vc_overlay_spinner").show()
        }, vc.removeOverlaySpinner = function() {
            vc.$overlaySpinner && vc.$overlaySpinner.hide()
        }, vc.visualComposerView = Backbone.View.extend({
            el: $("#wpb_wpbakery"),
            views: {},
            isEditorInFocus: !1,
            isKeydownEventAssigned: !1,
            disableFixedNav: !1,
            events: {
                "click #wpb-add-new-row": "createRow",
                "click #vc_post-settings-button": "editSettings",
                'click #vc_add-new-element, [data-vc-element="add-element-action"]': "addElement",
                "click #vc_fullscreen-button": "enterFullscreen",
                "click #vc_windowed-button": "leaveFullscreen",
                "click #vc_seo-button": "openSeo",
                'click [data-vc-element="add-text-block-action"]': "addTextBlock",
                "click .wpb_switch-to-composer": "switchComposer",
                "click #vc_templates-editor-button": "openTemplatesWindow",
                "click #vc_templates-more-layouts": "openTemplatesWindow",
                "click .vc_template[data-template_id] > .wpb_wrapper": "loadDefaultTemplate",
                "click #wpb-save-post": "save",
                "click .vc_control-preview": "preview",
                "click .vc_post-custom-layout": "changePostCustomLayout"
            },
            initializeAccessPolicy: function() {
                this.accessPolicy = {
                    be_editor: vc_user_access().editor("backend_editor"),
                    fe_editor: vc_frontend_enabled && vc_user_access().editor("frontend_editor"),
                    classic_editor: !vc_user_access().check("backend_editor", "disabled_ce_editor", void 0, !0)
                }
            },
            accessPolicyActions: function() {
                var _this, front = "",
                    back = "";
                this.accessPolicy.fe_editor && (front = '<a class="wpb_switch-to-front-composer" href="' + $("#wpb-edit-inline").attr("href") + '">' + window.i18nLocale.main_button_title_frontend_editor + "</a>"), this.accessPolicy.classic_editor ? this.accessPolicy.be_editor && (back = '<a class="wpb_switch-to-composer" href="#">' + window.i18nLocale.main_button_title_backend_editor + "</a>") : ($("#postdivrich").addClass("vc-disable-editor"), this.accessPolicy.be_editor && (_this = this, _.defer(function() {
                    _this.show(), _this.status = "shown"
                }))), (front || back || gutenberg) && (this.$buttonsContainer = $('<div class="composer-switch"><div class="composer-inner-switch"><span class="logo-icon"></span>' + back + front + "</div></div>").insertAfter("div#titlediv"), this.accessPolicy.classic_editor) && (this.$switchButton = this.$buttonsContainer.find(".wpb_switch-to-composer"), this.$switchButton.on("click", this.switchComposer))
            },
            initialize: function() {
                var _this = this;
                _.bindAll(this, "switchComposer", "dropButton", "processScroll", "updateRowsSorting", "updateElementsSorting"), this.accessPolicy = vc.accessPolicy, this.buildRelevance(), vc.events.on("shortcodes:add", vcAddShortcodeDefaultParams, this), vc.events.on("shortcodes:add", vc.atts.addShortcodeIdParam, this), vc.events.on("shortcodes:sync", vc.atts.addShortcodeIdParam, this), vc.events.on("shortcodes:add", this.addShortcode, this), vc.events.on("shortcodes:destroy", this.checkEmpty, this), vc.shortcodes.on("change:params", this.changeParamsEvents, this), vc.shortcodes.on("reset", this.addAll, this), $(document).on("wp-collapse-menu", function(e, params) {
                    "open" === params.state && _this.leaveFullscreen()
                }), this.render()
            },
            changeParamsEvents: function(model) {
                vc.events.triggerShortcodeEvents("update", model)
            },
            render: function() {
                return this.$buttonsContainer = $(".composer-switch"), this.$switchButton = this.$buttonsContainer.find(".wpb_switch-to-composer"), this.$vcStatus = $("#wpb_vc_js_status"), this.$metablock_content = $(".metabox-composer-content"), this.$content = $("#wpbakery_content"), this.$post = $("#postdivrich"), this.$loading_block = $("#vc_logo"), vc.add_element_block_view = new vc.AddElementUIPanelBackendEditor({
                    el: "#vc_ui-panel-add-element"
                }), vc.edit_element_block_view = new vc.EditElementUIPanel({
                    el: "#vc_ui-panel-edit-element"
                }), vc.templates_panel_view = new vc.TemplateWindowUIPanelBackendEditor({
                    el: "#vc_ui-panel-templates"
                }), vc.post_settings_view = new vc.PostSettingsUIPanelBackendEditor({
                    el: "#vc_ui-panel-post-settings"
                }), vc.preset_panel_view = new vc.PresetSettingsUIPanelFrontendEditor({
                    el: "#vc_ui-panel-preset"
                }), vc.post_seo_view = new vc.PostSettingsSeoUIPanel({
                    el: "#vc_ui-panel-post-seo"
                }), this.setSortable(), vc.is_mobile = 0 < $("body.mobile").length, vc.saved_custom_css = $("#wpb_custom_post_css_field").val(), vc.updateSettingsBadge(), _.defer(function() {
                    vc.events.trigger("app.render")
                }), $("body").on("click", $.proxy(this.handleBodyClick, this)), this
            },
            addAll: function() {
                this.views = {}, this.$content.removeClass("loading").empty(), this.addChild(!1), this.checkEmpty(), this.$loading_block.removeClass("vc_ui-wp-spinner"), this.$metablock_content.removeClass("vc_loading-shortcodes"), _.defer(function() {
                    vc.events.trigger("app.addAll")
                })
            },
            addChild: function(parent_id) {
                _.each(vc.shortcodes.where({
                    parent_id: parent_id
                }), function(shortcode) {
                    this.appendShortcode(shortcode), this.addChild(shortcode.get("id"))
                }, this), this.setSortable()
            },
            getView: function(model) {
                var view = new(_.isObject(vc.map[model.get("shortcode")]) && _.isString(vc.map[model.get("shortcode")].js_view) && vc.map[model.get("shortcode")].js_view.length && !_.isUndefined(window[window.vc.map[model.get("shortcode")].js_view]) ? window[window.vc.map[model.get("shortcode")].js_view] : vc.shortcode_view)({
                    model: model
                });
                return model.set({
                    view: view
                }), view
            },
            setDraggable: function() {
                $("#wpb-add-new-element, #wpb-add-new-row").draggable({
                    helper: function() {
                        return $('<div id="drag_placeholder"></div>').appendTo("body")
                    },
                    zIndex: 99999,
                    cursor: "move",
                    revert: "invalid",
                    start: function(event, ui) {
                        $("#drag_placeholder").addClass("column_placeholder").html(window.i18nLocale.drag_drop_me_in_column)
                    }
                })
            },
            setDropable: function() {
                this.$content.droppable({
                    greedy: !0,
                    accept: ".dropable_el,.dropable_row",
                    hoverClass: "wpb_ui-state-active",
                    drop: this.dropButton
                })
            },
            dropButton: function(event, ui) {
                ui.draggable.is("#wpb-add-new-element") ? this.addElement() : ui.draggable.is("#wpb-add-new-row") && this.createRow()
            },
            appendShortcode: function(model) {
                var view = this.getView(model),
                    params = _.extend(vc.getDefaults(model.get("shortcode")), model.get("params"));
                model.set("params", params, {
                    silent: !0
                }), params = !1 !== model.get("parent_id") && this.views[model.get("parent_id")], this.views[model.id] = view, model.get("parent_id") && this.views[model.get("parent_id")].unsetEmpty(), params ? params.addShortcode(view, "append") : this.$content.append(view.render().el), view.ready(), view.changeShortcodeParams(model), view.checkIsEmpty(), this.setNotEmpty()
            },
            addShortcode: function(model) {
                var view, _this, params = _.extend(vc.getDefaults(model.get("shortcode")), model.get("params"));
                model.set("params", params, {
                    silent: !0
                }), view = this.getView(model), params = !1 !== model.get("parent_id") && this.views[model.get("parent_id")], view.use_default_content = !0 !== model.get("cloned"), this.views[model.id] = view, params ? (params.addShortcode(view), params.checkIsEmpty(), _this = this, _.defer(function() {
                    view.changeShortcodeParams && view.changeShortcodeParams(model), view.ready(), view.checkIsEmpty(), _this.setSortable(), _this.setNotEmpty()
                })) : (this.addRow(view), _.defer(function() {
                    view.changeShortcodeParams && view.changeShortcodeParams(model), view.ready(), view.checkIsEmpty()
                }))
            },
            addRow: function(view) {
                var before_shortcode = _.last(vc.shortcodes.filter(function(shortcode) {
                    return !1 === shortcode.get("parent_id") && parseFloat(shortcode.get("order")) < parseFloat(this.get("order"))
                }, view.model));
                before_shortcode ? view.render().$el.insertAfter("[data-model-id=" + before_shortcode.id + "]") : this.$content.append(view.render().el)
            },
            addTextBlock: function(e) {
                var column, params;
                return e && e.preventDefault && e.preventDefault(), e = vc.shortcodes.create({
                    shortcode: "vc_row",
                    params: {}
                }), column = vc.shortcodes.create({
                    shortcode: "vc_column",
                    params: {
                        width: "1/1"
                    },
                    parent_id: e.id,
                    root_id: e.id
                }), params = vc.getDefaults("vc_column_text"), vc.shortcodes.create({
                    shortcode: "vc_column_text",
                    parent_id: column.id,
                    root_id: e.id,
                    params: params
                })
            },
            createRow: function() {
                var row = vc.shortcodes.create({
                    shortcode: "vc_row",
                    params: {}
                });
                return vc.shortcodes.create({
                    shortcode: "vc_column",
                    params: {
                        width: "1/1"
                    },
                    parent_id: row.id,
                    root_id: row.id
                }), row
            },
            addElement: function(e) {
                e && e.preventDefault && e.preventDefault(), vc.add_element_block_view.render(!1)
            },
            openTemplatesWindow: function(e) {
                e && e.preventDefault && e.preventDefault(), $(e.currentTarget).is("#vc_templates-more-layouts") && vc.templates_panel_view.once("show", function() {
                    $('[data-vc-ui-element-target="[data-tab=default_templates]"]').click()
                }), vc.templates_panel_view.render().show()
            },
            loadDefaultTemplate: function(e) {
                e && e.preventDefault && e.preventDefault(), vc.templates_panel_view.loadTemplate(e)
            },
            editSettings: function(e) {
                e && e.preventDefault && e.preventDefault(), vc.post_settings_view.render().show()
            },
            enterFullscreen: function() {
                var $body = $("body");
                $body.hasClass("folded") ? ($body.data("vcKeepMenuFolded", !0), $body.addClass("vc_fullscreen")) : $body.addClass("vc_fullscreen folded"), $("#vc_windowed-button").show(), $("#vc_fullscreen-button").hide()
            },
            leaveFullscreen: function() {
                var $body = $("body");
                $body.hasClass("vc_fullscreen") && ($body.data("vcKeepMenuFolded") ? ($body.removeClass("vc_fullscreen"), $body.removeData("vcKeepMenuFolded")) : $body.removeClass("vc_fullscreen folded"), $("#vc_windowed-button").hide(), $("#vc_fullscreen-button").show())
            },
            sortingStarted: function(event, ui) {
                $("#wpbakery_content").addClass("vc_sorting-started")
            },
            sortingStopped: function(event, ui) {
                var tag = ui.item.data("element_type"),
                    parent_tag = ui.placeholder.closest("[data-element_type]").data("element_type") || "";
                vc.check_relevance(parent_tag, tag) && parent_tag != tag || (ui.placeholder.addClass("vc_hidden-placeholder"), $(event.target).sortable("cancel")), $("#wpbakery_content").removeClass("vc_sorting-started")
            },
            updateElementsSorting: function(event, ui) {
                _.defer(function(app) {
                    var old_parent_id, parent = ui.item.parent().closest("[data-model-id]").data("model"),
                        model = ui.item.data("model"),
                        models = app.views[parent.id].$content.find("> [data-model-id]"),
                        i = 0;
                    _.isNull(ui.sender) || (old_parent_id = model.get("parent_id"), vc.storage.lock(), model.save({
                        parent_id: parent.id
                    }), old_parent_id && app.views[old_parent_id].checkIsEmpty(), app.views[parent.id].checkIsEmpty()), models.each(function() {
                        var shortcode = $(this).data("model");
                        vc.storage.lock(), shortcode.save({
                            order: i++
                        })
                    }), model.save()
                }, this)
            },
            updateRowsSorting: function(e, ui) {
                _.defer(function(app) {
                    var parentNode = ui.item.parent(),
                        $currentContainer = parentNode.closest("[data-model-id]"),
                        $currentContainer = !!$currentContainer.length && $currentContainer.data("model").get("id"),
                        model = ui.item.data("model"),
                        tag = ui.item.data("element_type"),
                        parent_tag = ui.item.parent().closest("[data-element_type]").data("element_type") || "";
                    vc.check_relevance(parent_tag, tag) && parent_tag != tag ? (parent_tag = model.get("parent_id"), parentNode.find(app.rowSortableSelector).each(function() {
                        var index = $(this).index();
                        vc.storage.lock(), $(this).data("model").save({
                            order: index
                        })
                    }), model.save({
                        parent_id: $currentContainer
                    }), parent_tag && app.views[parent_tag].checkIsEmpty(), $currentContainer && app.views[$currentContainer].checkIsEmpty()) : $(e.target).sortable("cancel")
                }, this)
            },
            renderPlaceholder: function(event, element) {
                var element = $(element).data("element_type"),
                    is_container = _.isObject(vc.map[element]) && (_.isBoolean(vc.map[element].is_container) && !0 === vc.map[element].is_container || !_.isEmpty(vc.map[element].as_parent));
                return $('<div class="vc_helper vc_helper-' + element + '"><i class="vc_general vc_element-icon' + (vc.map[element].icon ? " " + vc.map[element].icon : "") + '"' + (is_container ? ' data-is-container="true"' : "") + "></i> " + vc.map[element].name + "</div>").prependTo("body")
            },
            rowSortableSelector: "> .wpb_vc_row, > .vc_main-sortable-element",
            setSortable: function() {
                if (vc_user_access().partAccess("dragndrop")) return $(".wpb_main_sortable").sortable({
                    forcePlaceholderSize: !0,
                    placeholder: "widgets-placeholder",
                    cursor: "move",
                    connectWith: ".vc_section_container",
                    items: this.rowSortableSelector,
                    handle: ".vc_column-move",
                    cancel: ".vc-non-draggable-row",
                    distance: .5,
                    start: this.sortingStarted,
                    stop: this.sortingStopped,
                    update: this.updateRowsSorting,
                    tolerance: "intersect",
                    over: function(event, ui) {
                        var tag = ui.item.data("element_type"),
                            parent_tag = ui.placeholder.closest("[data-element_type]").data("element_type") || "";
                        if (!vc.check_relevance(parent_tag, tag) || parent_tag == tag) return ui.placeholder.addClass("vc_hidden-placeholder"), !1;
                        ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        })
                    },
                    out: function(event, ui) {
                        ui.placeholder.removeClass("vc_hidden-placeholder"), ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        })
                    }
                }), $(".wpb_column_container").sortable({
                    forcePlaceholderSize: !0,
                    forceHelperSize: !1,
                    connectWith: ".wpb_column_container",
                    placeholder: "vc_placeholder",
                    items: "> div.wpb_sortable,> div.vc-non-draggable",
                    helper: this.renderPlaceholder,
                    distance: 3,
                    cancel: ".vc-non-draggable",
                    scroll: !0,
                    scrollSensitivity: 70,
                    cursor: "move",
                    cursorAt: {
                        top: 20,
                        left: 16
                    },
                    tolerance: "intersect",
                    start: function() {
                        $("#wpbakery_content").addClass("vc_sorting-started"), $(".vc_not_inner_content").addClass("dragging_in")
                    },
                    stop: function(event, ui) {
                        $("#wpbakery_content").removeClass("vc_sorting-started"), $(".dragging_in").removeClass("dragging_in");
                        var tag = ui.item.data("element_type"),
                            parent_tag = ui.item.parent().closest("[data-element_type]").data("element_type") || "",
                            allowed_container_element = !!_.isUndefined(vc.map[parent_tag].allowed_container_element) || vc.map[parent_tag].allowed_container_element;
                        vc.check_relevance(parent_tag, tag) && parent_tag != tag || $(this).sortable("cancel"), _.isObject(vc.map[tag]) && (_.isBoolean(vc.map[tag].is_container) && !0 === vc.map[tag].is_container || !_.isEmpty(vc.map[tag].as_parent)) && !0 !== allowed_container_element && allowed_container_element !== ui.item.data("element_type").replace(/_inner$/, "") && $(this).sortable("cancel"), $(".vc_sorting-empty-container").removeClass("vc_sorting-empty-container")
                    },
                    update: this.updateElementsSorting,
                    over: function(event, ui) {
                        var tag = ui.item.data("element_type"),
                            parent_tag = ui.placeholder.closest("[data-element_type]").data("element_type"),
                            allowed_container_element = !!_.isUndefined(vc.map[parent_tag].allowed_container_element) || vc.map[parent_tag].allowed_container_element;
                        vc.check_relevance(parent_tag, tag) && parent_tag != tag || ui.placeholder.addClass("vc_hidden-placeholder"), _.isObject(vc.map[tag]) && (_.isBoolean(vc.map[tag].is_container) && !0 === vc.map[tag].is_container || !_.isEmpty(vc.map[tag].as_parent)) && !0 !== allowed_container_element && allowed_container_element !== ui.item.data("element_type").replace(/_inner$/, "") && ui.placeholder.addClass("vc_hidden-placeholder"), _.isNull(ui.sender) || !ui.sender.length || ui.sender.find("> [data-element_type]:not(.ui-sortable-helper):visible").length || ui.sender.addClass("vc_sorting-empty-container"), ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        })
                    },
                    out: function(event, ui) {
                        ui.placeholder.removeClass("vc_hidden-placeholder"), ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        })
                    }
                }).disableSelection(), this
            },
            setNotEmpty: function() {
                $("#vc_no-content-helper").addClass("vc_not-empty"), $("#vc_navbar").addClass("vc_not-empty")
            },
            setIsEmpty: function() {
                vc.views = {}, $("#vc_no-content-helper").removeClass("vc_not-empty"), $("#vc_navbar").removeClass("vc_not-empty")
            },
            checkEmpty: function(model) {
                if (_.isObject(model) && !1 !== model.get("parent_id") && model.get("parent_id") != model.id) {
                    model = this.views[model.get("parent_id")];
                    if (!model) return;
                    model.checkIsEmpty()
                }
                0 === vc.shortcodes.length ? this.setIsEmpty() : this.setNotEmpty()
            },
            switchComposer: function(e) {
                e && e.preventDefault && e.preventDefault(), "shown" === this.status ? (this.accessPolicy.can("classic_editor") && (_.isUndefined(this.$switchButton) || this.$switchButton.text(window.i18nLocale.main_button_title_backend_editor), _.isUndefined(this.$buttonsContainer) || this.$buttonsContainer.removeClass("vc_backend-status")), this.close(), this.status = "closed") : (this.accessPolicy.can("classic_editor") && (_.isUndefined(this.$switchButton) || this.$switchButton.text(window.i18nLocale.main_button_title_revert), _.isUndefined(this.$buttonsContainer) || this.$buttonsContainer.addClass("vc_backend-status")), this.show(), this.status = "shown")
            },
            show: function() {
                this.$el.show(), this.$post.addClass("vc-disable-editor"), this.$vcStatus.val("true"), this.navOnScroll(), vc.storage.isContentChanged() && (vc.undoRedoApi && vc.undoRedoApi.add(vc.storage.getContent()), vc.app.setLoading(), vc.app.views = {}, _.defer(function() {
                    vc.shortcodes.fetch({
                        reset: !0
                    }), vc.events.trigger("backendEditor.show")
                }))
            },
            setLoading: function() {
                this.setNotEmpty(), this.$loading_block.addClass("vc_ui-wp-spinner"), this.$metablock_content.addClass("vc_loading-shortcodes")
            },
            close: function() {
                this.$vcStatus.val("false"), this.$el.hide(), _.isObject(window.editorExpand) && (_.delay(function() {
                    window.scrollBy(0, -1)
                }, 17), _.delay(function() {
                    window.scrollBy(0, 1)
                }, 17)), this.$post.removeClass("vc-disable-editor"), _.defer(function() {
                    vc.events.trigger("backendEditor.close")
                })
            },
            checkVcStatus: function() {
                var isBackedEditor = new URL(window.location.href).searchParams.has("wpb-backend-editor");
                !vc.accessPolicy.can("be_editor") || vc.accessPolicy.can("classic_editor") && "true" !== this.$vcStatus.val() && !isBackedEditor || this.switchComposer()
            },
            setNavTop: function() {
                this.navTop = this.$nav.length && this.$nav.offset().top - 28
            },
            save: function() {
                $("#wpb-save-post").text(window.i18nLocale.loading), $("#publish").click()
            },
            preview: function() {
                $("#post-preview").click()
            },
            navOnScroll: function() {
                this.$nav = $("#vc_navbar"), this.setNavTop(), this.processScroll(), $(window).off("scroll.composer").on("scroll.composer", this.processScroll)
            },
            processScroll: function(e) {
                !0 === this.disableFixedNav ? this.$nav.removeClass("vc_subnav-fixed") : ((!this.navTop || this.navTop < 0) && this.setNavTop(), this.scrollTop = $(window).scrollTop() + 80, 0 < this.navTop && this.scrollTop >= this.navTop && !this.isFixed ? (this.isFixed = 1, this.$nav.addClass("vc_subnav-fixed")) : this.scrollTop <= this.navTop && this.isFixed && (this.isFixed = 0, this.$nav.removeClass("vc_subnav-fixed")))
            },
            buildRelevance: function() {
                vc.shortcode_relevance = {}, _.map(vc.map, function(object) {
                    _.isObject(object.as_parent) && _.isString(object.as_parent.only) && (vc.shortcode_relevance["parent_only_" + object.base] = object.as_parent.only.replace(/\s/, "").split(",")), _.isObject(object.as_parent) && _.isString(object.as_parent.except) && (vc.shortcode_relevance["parent_except_" + object.base] = object.as_parent.except.replace(/\s/, "").split(",")), _.isObject(object.as_child) && _.isString(object.as_child.only) && (vc.shortcode_relevance["child_only_" + object.base] = object.as_child.only.replace(/\s/, "").split(",")), _.isObject(object.as_child) && _.isString(object.as_child.except) && (vc.shortcode_relevance["child_except_" + object.base] = object.as_child.except.replace(/\s/, "").split(","))
                }), vc.check_relevance = function(tag, related_tag) {
                    return !(_.isArray(vc.shortcode_relevance["parent_only_" + tag]) && !_.contains(vc.shortcode_relevance["parent_only_" + tag], related_tag) || _.isArray(vc.shortcode_relevance["parent_except_" + tag]) && _.contains(vc.shortcode_relevance["parent_except_" + tag], related_tag) || _.isArray(vc.shortcode_relevance["child_only_" + related_tag]) && !_.contains(vc.shortcode_relevance["child_only_" + related_tag], tag) || _.isArray(vc.shortcode_relevance["child_except_" + related_tag]) && _.contains(vc.shortcode_relevance["child_except" + related_tag], tag))
                }
            },
            changePostCustomLayout: function(e) {
                var editor_wrapper, settings_layout;
                e && e.preventDefault && (e.preventDefault(), e = $(e.currentTarget).attr("data-post-custom-layout"), editor_wrapper = $("#wpb_wpbakery"), (settings_layout = $("#vc_ui-panel-post-settings .vc_post-custom-layout[data-post-custom-layout=" + e + "]")).addClass("vc-active-post-custom-layout"), settings_layout.siblings().removeClass("vc-active-post-custom-layout"), $("input[name=vc_post_custom_layout]").val(e), editor_wrapper.find(".vc_navbar").addClass("vc_post-custom-layout-selected"), editor_wrapper.find(".metabox-composer-content").addClass("vc_post-custom-layout-selected"))
            },
            handleEditorPaste: function(evt) {
                evt = evt.originalEvent.clipboardData.getData("text/plain") || "";
                vc.pasteShortcode(!1, !1, evt)
            },
            handleBodyClick: function(e) {
                this.isEditorInFocus = !!$(e.target).closest("#wpb_wpbakery, .vc_ui-panel-window").length, this.isEditorInFocus && !this.isKeydownEventAssigned ? ($("body").on("paste", this.handleEditorPaste), this.isKeydownEventAssigned = !0) : !this.isEditorInFocus && this.isKeydownEventAssigned && ($("body").off("paste", this.handleEditorPaste), this.isKeydownEventAssigned = !1)
            },
            openSeo: function(e) {
                e && e.preventDefault && e.preventDefault(), vc.post_seo_view.render()
            }
        }), $(function() {
            var $wpbVisualComposer = $("#wpb_wpbakery");
            $wpbVisualComposer.is("div") && (vc.app = new vc.visualComposerView({
                el: $wpbVisualComposer
            }), vc.accessPolicy.can("be_editor") && !vc_user_access().isBlockEditorIsEnabled() && vc.app.checkVcStatus(), $("#post").on("submit", function() {
                vc.storage.isChanged && (vc.storage.isChanged = !1, window.jQuery(window).off("beforeunload.vcSave"))
            }), vc.events.on("vc:backend_editor:show", function() {
                vc.app.show(), vc.app.status = "shown"
            }), vc.events.on("vc:backend_editor:switch", function() {
                vc.app.switchComposer()
            }))
        })
    }(window.jQuery),
    function($) {
        "use strict";
        var Shortcodes = vc.shortcodes;
        window.VcRowView = vc.shortcode_view.extend({
            change_columns_layout: !1,
            events: {
                'click > .vc_controls [data-vc-control="delete"]': "deleteShortcode",
                "click > .vc_controls .set_columns": "setColumns",
                'click > .vc_controls [data-vc-control="add"]': "addElement",
                'click > .vc_controls [data-vc-control="edit"]': "editElement",
                'click > .vc_controls [data-vc-control="clone"]': "clone",
                'click > .vc_controls [data-vc-control="copy"]': "copy",
                'click > .vc_controls [data-vc-control="paste"]': "paste",
                'click > .vc_controls [data-vc-control="move"]': "moveElement",
                'click > .vc_controls [data-vc-control="toggle"]': "toggleElement",
                "click > .wpb_element_wrapper .vc_controls": "openClosedRow"
            },
            convertRowColumns: function(layout) {
                var layout_split = layout.toString().split(/_/),
                    columns = Shortcodes.where({
                        parent_id: this.model.id
                    }),
                    new_columns = [],
                    new_layout = [],
                    new_width = "";
                return _.each(layout_split, function(value, i) {
                    var new_column, value = _.map(value.toString().split(""), function(v, i) {
                        return parseInt(v, 10)
                    });
                    new_width = 3 < value.length ? value[0] + "" + value[1] + "/" + value[2] + value[3] : 2 < value.length ? value[0] + "/" + value[1] + value[2] : value[0] + "/" + value[1], new_layout.push(new_width), value = _.extend(_.isUndefined(columns[i]) ? {} : columns[i].get("params"), {
                        width: new_width
                    }), vc.storage.lock(), new_column = Shortcodes.create({
                        shortcode: this.getChildTag(),
                        params: value,
                        parent_id: this.model.id
                    }), _.isObject(columns[i]) && _.each(Shortcodes.where({
                        parent_id: columns[i].id
                    }), function(shortcode) {
                        vc.storage.lock(), shortcode.save({
                            parent_id: new_column.id
                        }), vc.storage.lock(), shortcode.trigger("change_parent_id")
                    }), new_columns.push(new_column)
                }, this), layout_split.length < columns.length && _.each(columns.slice(layout_split.length), function(column) {
                    _.each(Shortcodes.where({
                        parent_id: column.id
                    }), function(shortcode) {
                        vc.storage.lock(), shortcode.save({
                            parent_id: _.last(new_columns).id
                        }), vc.storage.lock(), shortcode.trigger("change_parent_id")
                    })
                }), _.each(columns, function(shortcode) {
                    vc.storage.lock(), shortcode.destroy()
                }, this), this.model.save(), this.setActiveLayoutButton("" + layout), new_layout
            },
            changeShortcodeParams: function(model) {
                window.VcRowView.__super__.changeShortcodeParams.call(this, model), this.buildDesignHelpers(), this.setRowClasses()
            },
            setRowClasses: function() {
                var disable = this.model.getParam("disable_element"),
                    disableClass = "vc_hidden-xs vc_hidden-sm  vc_hidden-md vc_hidden-lg";
                this.disable_element_class && this.$el.removeClass(this.disable_element_class), _.isEmpty(disable) || (this.$el.addClass(disableClass), this.disable_element_class = disableClass)
            },
            designHelpersSelector: "> .vc_controls .column_toggle",
            buildDesignHelpers: function() {
                var image, color, matches, css = this.model.getParam("css"),
                    $elementToPrepend = this.$el.find(this.designHelpersSelector);
                this.$el.find("> .vc_controls .vc_row_color").remove(), this.$el.find("> .vc_controls .vc_row_image").remove(), (matches = css.match(/background\-image:\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (image = matches[1]), (matches = css.match(/background\-color:\s*([^\s\;]+)\b/)) && !_.isUndefined(matches[1]) && (color = matches[1]), (matches = css.match(/background:\s*([^\s]+)\b\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (color = matches[1], image = matches[2]), css = this.model.getParam("el_id"), this.$el.find("> .vc_controls .vc_row-hash-id").remove(), _.isEmpty(css) || $('<span class="vc_row-hash-id"></span>').text("#" + css).insertAfter($elementToPrepend), image && $('<span class="vc_row_image" style="background-image: url(' + image + ');" title="' + window.i18nLocale.row_background_image + '"></span>').insertAfter($elementToPrepend), color && $('<span class="vc_row_color" style="background-color: ' + color + '" title="' + window.i18nLocale.row_background_color + '"></span>').insertAfter($elementToPrepend)
            },
            addElement: function(e) {
                e && e.preventDefault && e.preventDefault(), Shortcodes.create({
                    shortcode: this.getChildTag(),
                    params: {},
                    parent_id: this.model.id
                }), this.setActiveLayoutButton(), this.$el.removeClass("vc_collapsed-row")
            },
            getChildTag: function() {
                return "vc_row_inner" === this.model.get("shortcode") ? "vc_column_inner" : "vc_column"
            },
            sortingSelector: "> [data-element_type=vc_column], > [data-element_type=vc_column_inner]",
            sortingSelectorCancel: ".vc-non-draggable-column",
            setSorting: function() {
                var _this;
                vc_user_access().partAccess("dragndrop") && (1 < (_this = this).$content.find(this.sortingSelector).length ? this.$content.removeClass("wpb-not-sortable").sortable({
                    forcePlaceholderSize: !0,
                    placeholder: "widgets-placeholder-column",
                    tolerance: "pointer",
                    cursor: "move",
                    items: this.sortingSelector,
                    cancel: this.sortingSelectorCancel,
                    distance: .5,
                    start: function(event, ui) {
                        $("#wpbakery_content").addClass("vc_sorting-started"), ui.placeholder.width(ui.item.width())
                    },
                    stop: function(event, ui) {
                        $("#wpbakery_content").removeClass("vc_sorting-started")
                    },
                    update: function() {
                        var $columns = $(_this.sortingSelector, _this.$content);
                        $columns.each(function() {
                            var model = $(this).data("model"),
                                index = $(this).index();
                            model.set("order", index), $columns.length - 1 > index && vc.storage.lock(), model.save()
                        })
                    },
                    over: function(event, ui) {
                        ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        }), ui.placeholder.removeClass("vc_hidden-placeholder")
                    },
                    beforeStop: function(event, ui) {}
                }) : (this.$content.hasClass("ui-sortable") && this.$content.sortable("destroy"), this.$content.addClass("wpb-not-sortable")))
            },
            validateCellsList: function(cells) {
                var b, return_cells = [],
                    cells = cells.replace(/\s/g, "").split("+");
                return 12 === _.reduce(_.map(cells, function(c) {
                    var converted_c;
                    return c.match(/^(vc_)?span\d?$/) ? !1 === (converted_c = vc_convert_column_span_size(c)) ? 1e3 : (b = converted_c.split(/\//), return_cells.push(b[0] + "" + b[1]), 12 * parseInt(b[0], 10) / parseInt(b[1], 10)) : c.match(/^[1-9]|1[0-2]\/[1-9]|1[0-2]$/) ? (b = c.split(/\//), return_cells.push(b[0] + "" + b[1]), 12 * parseInt(b[0], 10) / parseInt(b[1], 10)) : 1e4
                }), function(num, memo) {
                    return memo += num
                }, 0) && return_cells.join("_")
            },
            setActiveLayoutButton: function(column_layout) {
                column_layout = column_layout || _.map(vc.shortcodes.where({
                    parent_id: this.model.get("id")
                }), function(model) {
                    model = model.getParam("width");
                    return model ? model.replace(/\//, "") : "11"
                }).join("_"), this.$el.find("> .vc_controls .vc_active").removeClass("vc_active");
                column_layout = this.$el.find('> .vc_ [data-cells-mask="' + vc_get_column_mask(column_layout) + '"] [data-cells="' + column_layout + '"], > .vc_controls [data-cells-mask="' + vc_get_column_mask(column_layout) + '"][data-cells="' + column_layout + '"]');
                (column_layout.length ? column_layout : this.$el.find("> .vc_controls [data-cells-mask=custom]")).addClass("vc_active")
            },
            layoutEditor: function() {
                return _.isUndefined(vc.row_layout_editor) && (vc.row_layout_editor = new vc.RowLayoutUIPanelBackendEditor({
                    el: $("#vc_ui-panel-row-layout")
                })), vc.row_layout_editor
            },
            setColumns: function(e) {
                _.isObject(e) && e.preventDefault();
                var $parent, e = $(e.currentTarget);
                "custom" === e.data("cells") ? this.layoutEditor().render(this.model).show() : (vc.is_mobile && !($parent = e.parent()).hasClass("vc_visible") && ($parent.addClass("vc_visible"), $(document).off("click.vcRowColumnsControl").on("click.vcRowColumnsControl", function(e) {
                    $parent.removeClass("vc_visible")
                })), e.is(".vc_active") || (this.change_columns_layout = !0, _.defer(function(view, cells) {
                    view.convertRowColumns(cells)
                }, this, e.data("cells")))), this.$el.removeClass("vc_collapsed-row")
            },
            sizeRows: function() {
                var max_height = 45;
                $("> .wpb_vc_column, > .wpb_vc_column_inner", this.$content).each(function() {
                    var content_height = $(this).find("> .wpb_element_wrapper > .wpb_column_container").css({
                        minHeight: 0
                    }).height();
                    max_height < content_height && (max_height = content_height)
                }).each(function() {
                    $(this).find("> .wpb_element_wrapper > .wpb_column_container").css({
                        minHeight: max_height
                    })
                })
            },
            ready: function(e) {
                return window.VcRowView.__super__.ready.call(this, e), this
            },
            checkIsEmpty: function() {
                window.VcRowView.__super__.checkIsEmpty.call(this), this.setSorting()
            },
            changedContent: function(view) {
                if (this.change_columns_layout) return this;
                this.setActiveLayoutButton()
            },
            moveElement: function(e) {
                e && e.preventDefault && e.preventDefault()
            },
            toggleElement: function(e) {
                e && e.preventDefault && e.preventDefault(), this.$el.toggleClass("vc_collapsed-row")
            },
            openClosedRow: function(e) {
                this.$el.removeClass("vc_collapsed-row")
            },
            remove: function() {
                this.$content && (this.$content.data("uiSortable") && this.$content.sortable("destroy"), this.$content.data("uiDroppable")) && this.$content.droppable("destroy"), delete vc.app.views[this.model.id], window.VcRowView.__super__.remove.call(this)
            }
        }), window.VcColumnView = vc.shortcode_view.extend({
            events: {
                'click > .vc_controls [data-vc-control="delete"]': "deleteShortcode",
                'click > .vc_controls [data-vc-control="add"]': "addElement",
                'click > .vc_controls [data-vc-control="edit"]': "editElement",
                'click > .vc_controls [data-vc-control="clone"]': "clone",
                'click > .vc_controls [data-vc-control="copy"]': "copy",
                'click > .vc_controls [data-vc-control="paste"]': "paste",
                "click > .wpb_element_wrapper > .vc_empty-container": "addToEmpty"
            },
            current_column_width: !1,
            initialize: function(options) {
                window.VcColumnView.__super__.initialize.call(this, options), _.bindAll(this, "setDropable", "dropButton")
            },
            render: function() {
                return window.VcColumnView.__super__.render.call(this), this.current_column_width = this.model.get("params").width || "1/1", this.$el.attr("data-width", this.current_column_width), this.setEmpty(), this
            },
            changeShortcodeParams: function(model) {
                window.VcColumnView.__super__.changeShortcodeParams.call(this, model), this.setColumnClasses(), this.buildDesignHelpers()
            },
            designHelpersSelector: "> .vc_controls .column_add",
            buildDesignHelpers: function() {
                var matches, image, color, css = this.model.getParam("css"),
                    $column_toggle = this.$el.find(this.designHelpersSelector).get(0);
                this.$el.find("> .vc_controls .vc_column_color").remove(), this.$el.find("> .vc_controls .vc_column_image").remove(), (matches = css.match(/background\-image:\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (image = matches[1]), (matches = css.match(/background\-color:\s*([^\s\;]+)\b/)) && !_.isUndefined(matches[1]) && (color = matches[1]), (matches = css.match(/background:\s*([^\s]+)\b\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (color = matches[1], image = matches[2]), image && $('<span class="vc_column_image" style="background-image: url(' + image + ');" title="' + i18nLocale.column_background_image + '"></span>').insertBefore($column_toggle), color && $('<span class="vc_column_color" style="background-color: ' + color + '" title="' + i18nLocale.column_background_color + '"></span>').insertBefore($column_toggle)
            },
            setColumnClasses: function() {
                var current_css_class_width, offset = this.model.getParam("offset") || "",
                    width = this.model.getParam("width") || "1/1",
                    css_class_width = this.convertSize(width);
                this.current_offset_class && this.$el.removeClass(this.current_offset_class), this.current_column_width !== width && (current_css_class_width = this.convertSize(this.current_column_width), this.$el.attr("data-width", width).removeClass(current_css_class_width).addClass(css_class_width), this.current_column_width = width), offset.match(/vc_col\-sm\-\d+/) && this.$el.removeClass(css_class_width), _.isEmpty(offset) || this.$el.addClass(offset), this.current_offset_class = offset
            },
            addToEmpty: function(e) {
                e && e.preventDefault && e.preventDefault(), $(e.target).hasClass("vc_empty-container") && this.addElement(e)
            },
            setDropable: function() {
                return this.$content.droppable({
                    greedy: !0,
                    accept: "vc_column_inner" === this.model.get("shortcode") ? ".dropable_el" : ".dropable_el,.dropable_row",
                    hoverClass: "wpb_ui-state-active",
                    drop: this.dropButton
                }), this
            },
            dropButton: function(event, ui) {
                ui.draggable.is("#wpb-add-new-element") ? vc.add_element_block_view({
                    model: {
                        position_to_add: "end"
                    }
                }).show(this) : ui.draggable.is("#wpb-add-new-row") && this.createRow()
            },
            setEmpty: function() {
                this.$el.addClass("vc_empty-column"), "edit" !== vc_user_access().getState("shortcodes") && this.$content.addClass("vc_empty-container")
            },
            unsetEmpty: function() {
                this.$el.removeClass("vc_empty-column"), this.$content.removeClass("vc_empty-container")
            },
            checkIsEmpty: function() {
                Shortcodes.where({
                    parent_id: this.model.id
                }).length ? this.unsetEmpty() : this.setEmpty(), window.VcColumnView.__super__.checkIsEmpty.call(this)
            },
            createRow: function() {
                var column_params = {
                        width: "1/1"
                    },
                    row = Shortcodes.create({
                        shortcode: "vc_row_inner",
                        params: {},
                        parent_id: this.model.id
                    });
                return Shortcodes.create({
                    shortcode: "vc_column_inner",
                    params: column_params,
                    parent_id: row.id
                }), row
            },
            convertSize: function(width) {
                var width = width ? width.split("/") : [1, 1],
                    range = _.range(1, 13),
                    num = !_.isUndefined(width[0]) && 0 <= _.indexOf(range, parseInt(width[0], 10)) && parseInt(width[0], 10),
                    range = !_.isUndefined(width[1]) && 0 <= _.indexOf(range, parseInt(width[1], 10)) && parseInt(width[1], 10);
                return !1 !== num && !1 !== range ? "vc_col-sm-" + 12 * num / range : "vc_col-sm-12"
            },
            deleteShortcode: function(e) {
                var parent, parent_id = this.model.get("parent_id");
                if (e && e.preventDefault && e.preventDefault(), !0 !== confirm(window.i18nLocale.press_ok_to_delete_section)) return !1;
                this.model.destroy(), parent_id && !vc.shortcodes.where({
                    parent_id: parent_id
                }).length ? (parent = vc.shortcodes.get(parent_id), _.contains(["vc_column", "vc_column_inner"], parent.get("shortcode")) || parent.destroy()) : parent_id && (parent = vc.shortcodes.get(parent_id)) && parent.view && parent.view.setActiveLayoutButton && parent.view.setActiveLayoutButton()
            },
            remove: function() {
                this.$content && this.$content.data("uiSortable") && this.$content.sortable("destroy"), this.$content && this.$content.data("uiDroppable") && this.$content.droppable("destroy"), delete vc.app.views[this.model.id], window.VcColumnView.__super__.remove.call(this)
            }
        }), window.VcSectionView = VcColumnView.extend({
            designHelpersSelector: "> .vc_controls-row .vc_column-edit",
            setColumnClasses: function() {
                var disable = this.model.getParam("disable_element"),
                    disableClass = "vc_hidden-xs vc_hidden-sm  vc_hidden-md vc_hidden-lg";
                this.disable_element_class && this.$el.removeClass(this.disable_element_class), _.isEmpty(disable) || (this.$el.addClass(disableClass), this.disable_element_class = disableClass)
            },
            buildDesignHelpers: function() {
                var image, color, matches, css = this.model.getParam("css"),
                    $elementToPrepend = this.$el.find(this.designHelpersSelector);
                this.$el.find("> .vc_controls-row .vc_row_color").remove(), this.$el.find("> .vc_controls-row .vc_row_image").remove(), (matches = css.match(/background\-image:\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (image = matches[1]), (matches = css.match(/background\-color:\s*([^\s\;]+)\b/)) && !_.isUndefined(matches[1]) && (color = matches[1]), (matches = css.match(/background:\s*([^\s]+)\b\s*url\(([^\)]+)\)/)) && !_.isUndefined(matches[1]) && (color = matches[1], image = matches[2]), css = this.model.getParam("el_id"), this.$el.find("> .vc_controls-row .vc_row-hash-id").remove(), _.isEmpty(css) || $('<span class="vc_row-hash-id"></span>').text("#" + css).insertAfter($elementToPrepend), image && $('<span class="vc_row_image" style="background-image: url(' + image + ');" title="' + window.i18nLocale.row_background_image + '"></span>').insertAfter($elementToPrepend), color && $('<span class="vc_row_color" style="background-color: ' + color + '" title="' + window.i18nLocale.row_background_color + '"></span>').insertAfter($elementToPrepend)
            },
            checkIsEmpty: function() {
                window.VcSectionView.__super__.checkIsEmpty.call(this), this.setSorting()
            },
            setSorting: function() {
                var _this;
                vc_user_access().partAccess("dragndrop") && (_this = this).$content.sortable({
                    forcePlaceholderSize: !0,
                    placeholder: "widgets-placeholder",
                    connectWith: ".wpb_main_sortable,.wpb_vc_section .vc_section_container",
                    cursor: "move",
                    items: "> .wpb_vc_row",
                    handle: ".vc_column-move",
                    cancel: ".vc-non-draggable-row",
                    distance: .5,
                    scroll: !0,
                    scrollSensitivity: 70,
                    tolerance: "intersect",
                    update: function(event, ui) {
                        var $elements, tag = ui.item.data("element_type"),
                            parent_tag = ui.item.parent().closest("[data-element_type]").data("element_type");
                        vc.check_relevance(parent_tag, tag) && parent_tag != tag && ($elements = $("> div.wpb_sortable,> div.vc-non-draggable", _this.$content)).each(function() {
                            var old_parent_id, model = $(this).data("model"),
                                index = $(this).index();
                            model.set("order", index), $elements.length - 1 > index && vc.storage.lock(), _.isNull(ui.sender) || (index = ui.item.parent().closest("[data-model-id]").data("model"), old_parent_id = model.get("parent_id"), vc.storage.lock(), model.save({
                                parent_id: index.id
                            }), old_parent_id && vc.app.views[old_parent_id].checkIsEmpty(), vc.app.views[index.id].checkIsEmpty()), model.save()
                        })
                    },
                    stop: function(event, ui) {
                        $("#wpbakery_content").removeClass("vc_sorting-started"), $(".dragging_in").removeClass("dragging_in");
                        var tag = ui.item.data("element_type"),
                            ui = ui.item.parent().closest("[data-element_type]").data("element_type");
                        vc.check_relevance(ui, tag) && ui != tag || $(this).sortable("cancel"), $(".vc_sorting-empty-container").removeClass("vc_sorting-empty-container")
                    },
                    over: function(event, ui) {
                        var tag = ui.item.data("element_type"),
                            parent_tag = ui.placeholder.closest("[data-element_type]").data("element_type") || "",
                            allowed_container_element = !!_.isUndefined(vc.map[parent_tag].allowed_container_element) || vc.map[parent_tag].allowed_container_element;
                        return !vc.check_relevance(parent_tag, tag) || parent_tag == tag || _.isObject(vc.map[tag]) && (_.isBoolean(vc.map[tag].is_container) && !0 === vc.map[tag].is_container || !_.isEmpty(vc.map[tag].as_parent)) && !0 !== allowed_container_element && allowed_container_element !== ui.item.data("element_type").replace(/_inner$/, "") ? (ui.placeholder.addClass("vc_hidden-placeholder"), !1) : (_.isNull(ui.sender) || !ui.sender.length || ui.sender.find("> [data-element_type]:not(.ui-sortable-helper):visible").length || ui.sender.addClass("vc_sorting-empty-container"), void ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        }))
                    },
                    out: function(event, ui) {
                        ui.placeholder.removeClass("vc_hidden-placeholder"), ui.placeholder.css({
                            maxWidth: ui.placeholder.parent().width()
                        }), _.isNull(ui.sender) || !ui.sender.length || ui.sender.find("> [data-element_type]:not(.ui-sortable-helper):visible").length || ui.sender.addClass("vc_sorting-empty-container")
                    }
                })
            }
        }), window.VcAccordionView = vc.shortcode_view.extend({
            adding_new_tab: !1,
            events: {
                'click .tab_controls:not(".nested_tab_controls") .add_tab':'addTab',
                "click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit": "editElement",
                "click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone": "clone"
            },
            render: function() {
                return window.VcAccordionView.__super__.render.call(this), vc_user_access().shortcodeAll("vc_accordion_tab") ? vc_user_access().partAccess("dragndrop") && this.$content.sortable({
                    axis: "y",
                    handle: "h3",
                    stop: function(event, ui) {
                        ui.item.prev().triggerHandler("focusout"), $(this).find("> .wpb_sortable").each(function() {
                            $(this).data("model").save({
                                order: $(this).index()
                            })
                        })
                    }
                }) : this.$el.find(".tab_controls").hide(), this
            },
            changeShortcodeParams: function(model) {
                window.VcAccordionView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), model = !(!_.isString(model.collapsible) || "yes" !== model.collapsible), this.$content.hasClass("ui-accordion") && this.$content.accordion("option", "collapsible", model)
            },
            changedContent: function(view) {
                this.$content.hasClass("ui-accordion") && this.$content.accordion("destroy");
                var collapsible = !(!_.isString(this.model.get("params").collapsible) || "yes" !== this.model.get("params").collapsible);
                this.$content.accordion({
                    header:"h3:not('.wpb_vc_accordion_tab h3')",
                    navigation: !1,
                    autoHeight: !0,
                    heightStyle: "content",
                    collapsible: collapsible,
                    active: !1 === this.adding_new_tab && !0 !== view.model.get("cloned") ? 0 : view.$el.index()
                }), this.adding_new_tab = !1
            },
            addTab: function(e) {
                if (e && e.preventDefault && e.preventDefault(), !vc_user_access().shortcodeAll("vc_accordion_tab")) return !1;
                this.adding_new_tab = !0, vc.shortcodes.create({
                    shortcode: "vc_accordion_tab",
                    params: {
                        title: window.i18nLocale.section
                    },
                    parent_id: this.model.id
                })
            },
            _loadDefaults: function() {
                window.VcAccordionView.__super__._loadDefaults.call(this)
            }
        }), window.VcAccordionTabView = window.VcColumnView.extend({
            events: {
                "click > [data-element_type] > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > [data-element_type] >  .vc_controls .vc_control-btn-prepend": "addElement",
                "click > [data-element_type] >  .vc_controls .vc_control-btn-edit": "editElement",
                "click > [data-element_type] > .vc_controls .vc_control-btn-clone": "clone",
                "click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container": "addToEmpty"
            },
            setContent: function() {
                this.$content = this.$el.find("> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children")
            },
            changeShortcodeParams: function(model) {
                window.VcAccordionTabView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && _.isString(model.title) && this.$el.find("> h3 .tab-label").text(model.title)
            },
            setEmpty: function() {
                $("> [data-element_type]", this.$el).addClass("vc_empty-column"), "edit" !== vc_user_access().getState("shortcodes") && this.$content.addClass("vc_empty-container")
            },
            unsetEmpty: function() {
                $("> [data-element_type]", this.$el).removeClass("vc_empty-column"), this.$content.removeClass("vc_empty-container")
            }
        }),
		window.VcNestedAccordionView = vc.shortcode_view.extend({
			adding_new_tab:false,
			events:{
				'click .nested_tab_controls .add_tab':'addTab',
				'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete':'deleteShortcode',
				'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit':'editElement',
				'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone':'clone'
			},
			render:function () {
				window.VcNestedAccordionView.__super__.render.call(this);
				this.$content.sortable({
					axis:"y",
					handle:"h3",
					stop:function (event, ui) {
						// IE doesn't register the blur when sorting
						// so trigger focusout handlers to remove .ui-state-focus
						ui.item.prev().triggerHandler("focusout");
						$(this).find('> .wpb_sortable').each(function () {
							var shortcode = $(this).data('model');
							shortcode.save({'order':$(this).index()}); // Optimize
						});
					}
				});
				return this;
			},
			changeShortcodeParams:function (model) {
				window.VcNestedAccordionView.__super__.changeShortcodeParams.call(this, model);
				var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
				if (this.$content.hasClass('ui-accordion')) {
					this.$content.accordion("option", "collapsible", collapsible);
				}
			},
			changedContent:function (view) {
				if (this.$content.hasClass('ui-accordion')) this.$content.accordion('destroy');
				var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
				this.$content.accordion({
					header:"h3",
					navigation:false,
					autoHeight:true,
					heightStyle: "content",
					collapsible:collapsible,
					active:this.adding_new_tab === false && view.model.get('cloned') !== true ? 0 : view.$el.index()
				});
				this.adding_new_tab = false;
			},
			addTab:function (e) {
				this.adding_new_tab = true;
				e.preventDefault();
				vc.shortcodes.create({shortcode:'vc_nested_accordion_tab', params:{title:window.i18nLocale.section}, parent_id:this.model.id});
			},
			_loadDefaults:function () {
				window.VcNestedAccordionView.__super__._loadDefaults.call(this);
			}
		}),
		
		window.VcNestedAccordionTabView = window.VcColumnView.extend({
			events:{
				'click > [data-element_type] > .vc_controls .vc_control-btn-delete':'deleteShortcode',
				'click > [data-element_type] >  .vc_controls .vc_control-btn-prepend':'addElement',
				'click > [data-element_type] >  .vc_controls .vc_control-btn-edit':'editElement',
				'click > [data-element_type] > .vc_controls .vc_control-btn-clone':'clone',
				'click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container':'addToEmpty'
			},
			setContent:function () {
				this.$content = this.$el.find('> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children');
			},
			changeShortcodeParams:function (model) {
				var params = model.get('params');
				window.VcNestedAccordionTabView.__super__.changeShortcodeParams.call(this, model);
				if (_.isObject(params) && _.isString(params.title)) {
					this.$el.find('> h3 .tab-label').text(params.title);
				}
			},
			setEmpty:function () {
				$('> [data-element_type]', this.$el).addClass('vc_empty-column');
				this.$content.addClass('vc_empty-container');
			},
			unsetEmpty:function () {
				$('> [data-element_type]', this.$el).removeClass('vc_empty-column');
				this.$content.removeClass('vc_empty-container');
			}
        }), window.VcMessageView = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                var $wrapper;
                window.VcMessageView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), $wrapper = this.$el.find("> .wpb_element_wrapper").removeClass(_.values(this.params.color.value).join(" ")), _.isObject(model) && _.isString(model.color) && $wrapper.addClass(model.color)
            }
        }), window.VcMessageView_Backend = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                var params, classes, iconClass, color;
                switch (window.VcMessageView_Backend.__super__.changeShortcodeParams.call(this, model), params = model.get("params"), model = this.$el.find("> .wpb_element_wrapper"), classes = ["vc_message_box"], _.isUndefined(params.message_box_style) && (params.message_box_style = "classic"), _.isUndefined(params.message_box_color) && (params.message_box_color = "alert-info"), params.style ? "3d" === params.style ? (params.message_box_style = "3d", params.style = "rounded") : "outlined" === params.style ? (params.message_box_style = "outline", params.style = "rounded") : "square_outlined" === params.style && (params.message_box_style = "outline", params.style = "square") : params.style = "rounded", classes.push("vc_message_box-" + params.style), params.message_box_style && classes.push("vc_message_box-" + params.message_box_style), model.attr("class", "wpb_element_wrapper"), model.find(".vc_message_box-icon").remove(), iconClass = _.isUndefined(params["icon_" + params.icon_type]) ? "fa fa-info-circle" : params["icon_" + params.icon_type], color = params.color, params.color) {
                    case "info":
                        iconClass = "fa fa-info-circle";
                        break;
                    case "alert-info":
                        iconClass = "vc_pixel_icon vc_pixel_icon-info";
                        break;
                    case "success":
                        iconClass = "fa fa-check";
                        break;
                    case "alert-success":
                        iconClass = "vc_pixel_icon vc_pixel_icon-tick";
                        break;
                    case "warning":
                        iconClass = "fa fa-exclamation-triangle";
                        break;
                    case "alert-warning":
                        iconClass = "vc_pixel_icon vc_pixel_icon-alert";
                        break;
                    case "danger":
                        iconClass = "fa fa-times";
                        break;
                    case "alert-danger":
                        iconClass = "vc_pixel_icon vc_pixel_icon-explanation";
                        break;
                    default:
                        color = params.message_box_color
                }
                classes.push("vc_color-" + color), model.addClass(classes.join(" ")), model.prepend($('<div class="vc_message_box-icon"><i class="' + iconClass + '"></i></div>'))
            }
        }), window.VcTextSeparatorView = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                window.VcTextSeparatorView.__super__.changeShortcodeParams.call(this, model), model = model.get("params");
                var $find = this.$el.find("> .wpb_element_wrapper");
                _.isObject(model) && _.isString(model.title_align) && $find.removeClass(_.values(this.params.title_align.value).join(" ")).addClass(model.title_align), _.isObject(model) && _.isString(model.add_icon) && "true" === model.add_icon && ((model = $('<i class="' + model["i_icon_" + model.i_type] + '" ></i>')).prependTo($find.find("[name=title]")), model.after(" "))
            }
        }), window.VcCallToActionView = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                window.VcCallToActionView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && _.isString(model.position) && this.$el.find("> .wpb_element_wrapper").removeClass(_.values(this.params.position.value).join(" ")).addClass(model.position)
            }
        }), window.VcCallToActionView3 = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                var $adminLabel;
                window.VcCallToActionView3.__super__.changeShortcodeParams.call(this, model), model = _.extend({
                    add_icon: "",
                    i_type: ""
                }, model.get("params")), $adminLabel = this.$el.find(".vc_admin_label.admin_label_i_type"), _.isEmpty(model.add_icon) ? $adminLabel.addClass("hidden-label").hide() : _.isEmpty(model.i_type) || _.isEmpty(model["i_icon_" + model.i_type]) || (model = vc_toTitleCase(model.i_type) + ' - <i class="' + model["i_icon_" + model.i_type] + '"></i>', $adminLabel.html("<label>" + $adminLabel.find("label").text() + "</label>: " + model), $adminLabel.show().removeClass("hidden-label"))
            }
        }), window.VcToggleView = vc.shortcode_view.extend({
            events: function() {
                return _.extend({
                    "click .vc_toggle_title": "toggle",
                    "click .toggle_title": "toggle"
                }, window.VcToggleView.__super__.events)
            },
            toggle: function(e) {
                e && e.preventDefault && e.preventDefault(), $(e.currentTarget).toggleClass("vc_toggle_title_active"), $(".vc_toggle_content", this.$el).slideToggle(500)
            },
            changeShortcodeParams: function(model) {
                window.VcToggleView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && _.isString(model.open) && "true" === model.open && $(".vc_toggle_title", this.$el).addClass("vc_toggle_title_active").next().show()
            }
        }), window.VcButtonView = vc.shortcode_view.extend({
            events: function() {
                return _.extend({
                    "click button": "buttonClick"
                }, window.VcToggleView.__super__.events)
            },
            buttonClick: function(e) {
                e && e.preventDefault && e.preventDefault()
            },
            changeShortcodeParams: function(model) {
                var el_class;
                window.VcButtonView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && (el_class = model.color + " " + model.size + " " + model.icon, this.$el.find(".wpb_element_wrapper").removeClass(el_class), this.$el.find("button.title").attr({
                    class: "title textfield wpb_button " + el_class
                }), "none" !== model.icon && 0 === this.$el.find("button i.icon").length ? this.$el.find("button.title").append('<i class="icon"></i>') : this.$el.find("button.title i.icon").remove())
            }
        }), window.VcButton2View = vc.shortcode_view.extend({
            events: function() {
                return _.extend({
                    "click button": "buttonClick"
                }, window.VcToggleView.__super__.events)
            },
            buttonClick: function(e) {
                e && e.preventDefault && e.preventDefault()
            },
            changeShortcodeParams: function(model) {
                window.VcButton2View.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && (model = (model.color ? "vc_btn_" + model.color : "") + " " + (model.color ? "vc_btn-" + model.color : "") + " " + (model.size ? "vc_btn-" + model.size : "") + " " + (model.size ? "vc_btn_" + model.size : "") + " " + (model.style ? "vc_btn_" + model.style : ""), this.$el.find(".wpb_element_wrapper").removeClass(model), this.$el.find("button.title").attr({
                    class: "title textfield vc_btn  " + model
                }))
            }
        }), window.VcButton3View = vc.shortcode_view.extend({
            buttonTemplate: !1,
            buttonTemplateCompiled: !1,
            $wrapper: !1,
            events: function() {
                return _.extend({
                    "click .vc_btn3": "buttonClick"
                }, window.VcToggleView.__super__.events)
            },
            buttonClick: function(e) {
                e && e.preventDefault && e.preventDefault()
            },
            changeShortcodeParams: function(model) {
                var params;
                window.VcButton3View.__super__.changeShortcodeParams.call(this, model), params = _.extend({}, model.get("params")), this.buttonTemplate || (this.buttonTemplate = this.$el.find(".vc_btn3-container").html(), this.buttonTemplateCompiled = vc.template(this.buttonTemplate, vc.templateOptions.custom)), this.$wrapper || (this.$wrapper = this.$el.find(".wpb_element_wrapper")), _.isObject(params) && (params.title && _.isEmpty(params.title.trim()) && (params.title = '<span class="vc_btn3-placeholder">&nbsp;</span>'), "custom" === params.style ? (params.color = void 0, _.isEmpty(params.custom_background) && _.isEmpty(params.custom_text) && (params.color = "grey")) : "outline-custom" === params.style && (params.color = void 0, _.isEmpty(params.outline_custom_color)) && _.isEmpty(params.outline_custom_hover_background) && _.isEmpty(params.outline_custom_hover_text) && (params.style = "outline", params.color = "grey"), model = $(this.buttonTemplateCompiled({
                    params: params
                })), "custom" === params.style ? ("undefined" !== params.custom_background && model.css("background-color", params.custom_background), "undefined" !== params.custom_text && model.css("color", params.custom_text)) : "outline-custom" === params.style && model.css({
                    "background-color": "transparent",
                    "border-color": params.outline_custom_color,
                    color: params.outline_custom_color
                }).hover(function() {
                    $(this).css({
                        "background-color": params.outline_custom_hover_background,
                        "border-color": params.outline_custom_hover_background,
                        color: params.outline_custom_hover_text
                    })
                }, function() {
                    $(this).css({
                        "background-color": "transparent",
                        "border-color": params.outline_custom_color,
                        color: params.outline_custom_color
                    })
                }), this.$wrapper.find(".vc_btn3-container").html(model))
            }
        }), window.VcTabsView = vc.shortcode_view.extend({
            new_tab_adding: !1,
            events: {
                'click .tabs_controls .add_tab:not(".nested_tab_controls .add_tab")':'addTab',
                "click > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > .vc_controls .vc_control-btn-edit": "editElement",
                "click > .vc_controls .vc_control-btn-clone": "clone"
            },
            initialize: function(params) {
                window.VcTabsView.__super__.initialize.call(this, params), _.bindAll(this, "stopSorting")
            },
            render: function() {
                return window.VcTabsView.__super__.render.call(this), this.$tabs = this.$el.find(".wpb_tabs_holder"), this.createAddTabButton(), this
            },
            ready: function(e) {
                window.VcTabsView.__super__.ready.call(this, e)
            },
            createAddTabButton: function() {
                var new_tab_button_id = Date.now() + "-" + Math.floor(11 * Math.random());
                this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>'), this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls")), vc_user_access().shortcodeAll("vc_tab") || this.$add_button.hide()
            },
            addTab: function(e) {
                var tabs_count;
                return e && e.preventDefault && e.preventDefault(), vc_user_access().shortcodeAll("vc_tab") && (this.new_tab_adding = !0, e = window.i18nLocale.tab, tabs_count = this.$tabs.find("[data-element_type=vc_tab]").length, tabs_count = Date.now() + "-" + tabs_count + "-" + Math.floor(11 * Math.random()), vc.shortcodes.create({
                    shortcode: "vc_tab",
                    params: {
                        title: e,
                        tab_id: tabs_count
                    },
                    parent_id: this.model.id
                })), !1
            },
            stopSorting: function(event, ui) {
                var shortcode;
                this.$tabs.find("ul.tabs_controls li:not(.add_tab_block)").each(function(index) {
                    $(this).find("a").attr("href").replace("#", "");
                    shortcode = vc.shortcodes.get($("[id=" + $(this).attr("aria-controls") + "]").data("model-id")), vc.storage.lock(), shortcode.save({
                        order: $(this).index()
                    })
                }), shortcode && shortcode.save()
            },
            changedContent: function(view) {
                var params = view.model.get("params");
                this.$tabs.hasClass("ui-tabs") || (this.$tabs.tabs({
					activate: function (event, ui) {
						var newPanelSelector = $(":first-child", ui.newTab).attr("href").substr(1);
						var oldPanelSelector = $(":first-child", ui.oldTab).attr("href").substr(1);
						if(newPanelSelector.substr(4,4)=="http")
							$("[id='"+newPanelSelector+"']").css("display", "block");
						if(oldPanelSelector.substr(4,4)=="http")
							$("[id='"+oldPanelSelector+"']").css("display", "none");
					},
                    select: function(event, ui) {
                        return !$(ui.tab).hasClass("add_tab")
                    }
                }), this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs), vc_user_access().shortcodeAll("vc_tab") && this.$tabs.find(".ui-tabs-nav").sortable({
                    axis: "vc_tour" === this.$tabs.closest("[data-element_type]").data("element_type") ? "y" : "x",
                    update: this.stopSorting,
                    items: "> li:not(.add_tab_block)"
                })), !0 === view.model.get("cloned") ? (view.model.get("cloned_from"), view = $(".tabs_controls > .add_tab_block", this.$content), view = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(view), this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", view.index())) : ($("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(this.$add_button), this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", this.new_tab_adding ? $(".ui-tabs-nav li", this.$content).length - 2 : 0)), this.new_tab_adding = !1
            },
            cloneModel: function(model, parent_id, save_order) {
                var model_clone, save_order = _.isBoolean(save_order) && !0 === save_order ? model.get("order") : parseFloat(model.get("order")) + vc.clone_index,
                    params = _.extend({}, model.get("params")),
                    tag = model.get("shortcode");
                return "vc_tab" === tag && _.extend(params, {
                    tab_id: Date.now() + "-" + this.$tabs.find("[data-element-type=vc_tab]").length + "-" + Math.floor(11 * Math.random())
                }), model_clone = Shortcodes.create({
                    shortcode: tag,
                    id: vc_guid(),
                    parent_id: parent_id,
                    order: save_order,
                    cloned: "vc_tab" !== tag,
                    cloned_from: model.toJSON(),
                    params: params
                }), _.each(Shortcodes.where({
                    parent_id: model.id
                }), function(shortcode) {
                    this.cloneModel(shortcode, model_clone.get("id"), !0)
                }, this), model_clone
            }
        }),
		window.VcNestedTabsView = vc.shortcode_view.extend({
			new_tab_adding:false,
			events:{
			   'click .add_tab':'addTab',
				'click > .vc_controls .vc_control-btn-delete':'deleteShortcode',
				'click > .vc_controls .vc_control-btn-edit':'editElement',
				'click > .vc_controls .vc_control-btn-clone':'clone'
			},
			initialize:function (params) {
				window.VcNestedTabsView.__super__.initialize.call(this, params);
				_.bindAll(this, 'stopSorting');
			},
			render:function () {
				window.VcNestedTabsView.__super__.render.call(this);
				this.$tabs = this.$el.find('.wpb_nested_tabs_holder');
				this.createAddTabButton();
				return this;
			},
			ready:function (e) {
				window.VcNestedTabsView.__super__.ready.call(this, e);
			},
			createAddTabButton:function () {
				var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
				this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
				this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".nested_tabs_controls"));
			},
			addTab:function (e) {
				e.preventDefault();
				this.new_tab_adding = true;
				var tab_title = window.i18nLocale.tab,
					tabs_count = this.$tabs.find('[data-element_type=vc_nested_tab]').length,
					tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
				vc.shortcodes.create({shortcode:'vc_nested_tab', params:{title:tab_title, tab_id:tab_id}, parent_id:this.model.id});
				return false;
			},
			stopSorting:function (event, ui) {
				var shortcode;
				this.$tabs.find('ul.nested_tabs_controls li:not(.add_tab_block)').each(function (index) {
					var href = $(this).find('a').attr('href').replace("#", "");
					// $('#' + href).appendTo(this.$tabs);
					shortcode = vc.shortcodes.get($('[id=' + $(this).attr('aria-controls') + ']').data('model-id'));
					vc.storage.lock();
					shortcode.save({'order':$(this).index()}); // Optimize
				});
				shortcode.save();
			},
			changedContent:function (view) {
				var params = view.model.get('params');
				if (!this.$tabs.hasClass('ui-tabs')) {
					this.$tabs.tabs({
						activate: function (event, ui) {
							var newPanelSelector = $(":first-child", ui.newTab).attr("href").substr(1);
							var oldPanelSelector = $(":first-child", ui.oldTab).attr("href").substr(1);
							if(newPanelSelector.substr(4,4)=="http")
								$("[id='"+newPanelSelector+"']").css("display", "block");
							if(oldPanelSelector.substr(4,4)=="http")
								$("[id='"+oldPanelSelector+"']").css("display", "none");
						},
						select:function (event, ui) {
							if ($(ui.tab).hasClass('add_tab')) {
								return false;
							}
							return true;
						}
					});
					this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
					this.$tabs.find(".ui-tabs-nav").sortable({
						axis:(this.$tabs.closest('[data-element_type]').data('element_type') == 'vc_tour' ? 'y' : 'x'),
						update:this.stopSorting,
						items:"> li:not(.add_tab_block)"
					});
				}
				
				
				if (view.model.get('cloned') === true) {
					var cloned_from = view.model.get('cloned_from'),
						$after_tab = $('[href="#tab-' + cloned_from.params.tab_id + '"]', this.$content).parent();
					var $new_tab;
					if(typeof($after_tab.prop("tagName"))!="undefined")
						$new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertAfter($after_tab);
					else
						$new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(this.$add_button);
					this.$tabs.tabs('refresh');
					this.$tabs.tabs("option", 'active', $new_tab.index());
				} else {
					$("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
						.insertBefore(this.$add_button);
					this.$tabs.tabs('refresh');
					this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.nested_tabs_controls li', this.$content).length - 2 : 0);

				}
				
				/*if (view.model.get('cloned') === true) {
					var cloned_from = view.model.get('cloned_from'),
						$tab_controls = $('.tabs_controls > .add_tab_block', this.$content),
						$new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore($tab_controls);
					this.$tabs.tabs('refresh');
					this.$tabs.tabs("option", 'active', $new_tab.index());
				} else {
					$("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
						.insertBefore(this.$add_button);
					this.$tabs.tabs('refresh');
					this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.nested_tabs_controls li', this.$content).length - 2 : 0);

				}*/
				this.new_tab_adding = false;
			},
			cloneModel:function (model, parent_id, save_order) {
				var shortcodes_to_resort = [],
					new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
					model_clone,
					new_params = _.extend({}, model.get('params'));
				if (model.get('shortcode') === 'vc_nested_tab') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element-type=vc_nested_tab]').length + '-' + Math.floor(Math.random() * 11)});
				model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') !== 'vc_nested_tab'), cloned_from:model.toJSON(), params:new_params});
				_.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
					this.cloneModel(shortcode, model_clone.get('id'), true);
				}, this);
				return model_clone;
			}
        }), window.VcTabView = window.VcColumnView.extend({
            events: {
                "click > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > .vc_controls .vc_control-btn-prepend": "addElement",
                "click > .vc_controls .vc_control-btn-edit": "editElement",
                "click > .vc_controls .vc_control-btn-clone": "clone",
                "click > .wpb_element_wrapper > .vc_empty-container": "addToEmpty"
            },
            render: function() {
                var params = this.model.get("params");
                return window.VcTabView.__super__.render.call(this), params.tab_id || (params.tab_id = Date.now() + "-" + Math.floor(11 * Math.random()), this.model.save("params", params)), this.id = "tab-" + params.tab_id, this.$el.attr("id", this.id), this
            },
            ready: function(e) {
                window.VcTabView.__super__.ready.call(this, e), this.$tabs = this.$el.closest(".wpb_tabs_holder");
                this.model.get("params");
                return this
            },
            changeShortcodeParams: function(model) {
                window.VcTabView.__super__.changeShortcodeParams.call(this, model), model = model.get("params"), _.isObject(model) && _.isString(model.title) && _.isString(model.tab_id) && $('.ui-tabs-nav [href="#tab-' + model.tab_id + '"]').text(model.title)
            },
            deleteShortcode: function(e) {
                e && e.preventDefault && e.preventDefault();
                var current_tab_index, e = confirm(window.i18nLocale.press_ok_to_delete_section),
                    parent_id = this.model.get("parent_id");
                return !0 === e && (this.model.destroy(), vc.shortcodes.where({
                    parent_id: parent_id
                }).length ? (e = this.model.get("params"), current_tab_index = $('[href="#tab-' + e.tab_id + '"]', this.$tabs).parent().index(), $('[href="#tab-' + e.tab_id + '"]').parent().remove(), 0 < (e = this.$tabs.find(".ui-tabs-nav li:not(.add_tab_block)").length) && this.$tabs.tabs("refresh"), void(current_tab_index < e ? this.$tabs.tabs("option", "active", current_tab_index) : 0 < e && this.$tabs.tabs("option", "active", e - 1))) : (vc.shortcodes.get(parent_id).destroy(), !1))
            },
            cloneModel: function(model, parent_id, save_order) {
                var model_clone, save_order = _.isBoolean(save_order) && !0 === save_order ? model.get("order") : parseFloat(model.get("order")) + vc.clone_index,
                    params = _.extend({}, model.get("params")),
                    tag = model.get("shortcode");
                return "vc_tab" === tag && _.extend(params, {
                    tab_id: Date.now() + "-" + this.$tabs.find("[data-element_type=vc_tab]").length + "-" + Math.floor(11 * Math.random())
                }), model_clone = Shortcodes.create({
                    shortcode: tag,
                    parent_id: parent_id,
                    order: save_order,
                    cloned: !0,
                    cloned_from: model.toJSON(),
                    params: params
                }), _.each(Shortcodes.where({
                    parent_id: model.id
                }), function(shortcode) {
                    this.cloneModel(shortcode, model_clone.get("id"), !0)
                }, this), model_clone
            }
        }),
		window.VcNestedTabView = window.VcColumnView.extend({
			events:{
			  'click > .vc_controls .vc_control-btn-delete':'deleteShortcode',
			  'click > .vc_controls .vc_control-btn-prepend':'addElement',
			  'click > .vc_controls .vc_control-btn-edit':'editElement',
			  'click > .vc_controls .vc_control-btn-clone':'clone',
			  'click > .wpb_element_wrapper > .vc_empty-container':'addToEmpty'
			},
			render:function () {
				var params = this.model.get('params');
				window.VcNestedTabView.__super__.render.call(this);
				if(!params.tab_id || params.tab_id.indexOf('def') != -1) {
				  params.tab_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
				  this.model.save('params', params);
				}
				this.id = 'tab-' + params.tab_id;
				this.$el.attr('id', this.id);
				return this;
			},
			ready:function (e) {
				window.VcNestedTabView.__super__.ready.call(this, e);
				this.$tabs = this.$el.closest('.wpb_nested_tabs_holder');
				var params = this.model.get('params');
				return this;
			},
			changeShortcodeParams:function (model) {
				var params = model.get('params');
				window.VcNestedTabView.__super__.changeShortcodeParams.call(this, model);
				if (_.isObject(params) && _.isString(params.title) && _.isString(params.tab_id)) {
					$('.ui-tabs-nav [href="#tab-' + params.tab_id + '"]').text(params.title);
				}
			},
			deleteShortcode:function (e) {
				_.isObject(e) && e.preventDefault();
				var answer = confirm(window.i18nLocale.press_ok_to_delete_section),
					parent_id = this.model.get('parent_id');
				if (answer !== true) return false;
				this.model.destroy();
				if(!vc.shortcodes.where({parent_id: parent_id}).length) {
				  vc.shortcodes.get(parent_id).destroy();
				  return false;
				}
				var params = this.model.get('params'),
					current_tab_index = $('[href="#tab-' + params.tab_id + '"]', this.$tabs).parent().index();
				$('[href="#tab-' + params.tab_id + '"]').parent().remove();
				var tab_length = this.$tabs.find('.ui-tabs-nav li:not(.add_tab_block)').length;
				if(tab_length > 0) {
					this.$tabs.tabs('refresh');
				}
				if (current_tab_index < tab_length) {
					this.$tabs.tabs("option", "active", current_tab_index);
				} else if (tab_length > 0) {
					this.$tabs.tabs("option", "active", tab_length - 1);
				}

			},
			cloneModel:function (model, parent_id, save_order) {
				var shortcodes_to_resort = [],
					new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
					new_params = _.extend({}, model.get('params'));
				if (model.get('shortcode') === 'vc_nested_tab') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=vc_nested_tab]').length + '-' + Math.floor(Math.random() * 11)});
				var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
				_.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
					this.cloneModel(shortcode, model_clone.id, true);
				}, this);
				return model_clone;
			}
        }), window.VcIconElementView_Backend = vc.shortcode_view.extend({
            changeShortcodeParams: function(model) {
                var tag = model.get("shortcode"),
                    params = model.get("params"),
                    tag = vc.map[tag];
                _.isArray(tag.params) && _.each(tag.params, function(param_settings) {
                    var value, $admin_label;
                    !_.isUndefined(param_settings.admin_label) && param_settings.admin_label && (param_settings = param_settings.param_name, value = params[param_settings], ($admin_label = this.$el.find("> .wpb_element_wrapper").children(".admin_label_" + param_settings)).length) && ("" === value || _.isUndefined(value) ? $admin_label.hide().addClass("hidden-label") : ("type" !== param_settings || _.isUndefined(params["icon_" + value]) || (value = vc_toTitleCase(value) + " - <i class='" + params["icon_" + value] + "'></i>"), $admin_label.html("<label>" + $admin_label.find("label").text() + "</label>: " + value), $admin_label.show().removeClass("hidden-label")))
                }, this), tag = vc.app.views[this.model.get("parent_id")], !1 !== model.get("parent_id") && _.isObject(tag) && tag.checkIsEmpty()
            }
        }), window.VcBackendTtaViewInterface = vc.shortcode_view.extend({
            sortableSelector: !1,
            $sortable: !1,
            $navigation: !1,
            defaultSectionTitle: window.i18nLocale.tab,
            sortableUpdateModelIdSelector: "data-vc-target-model-id",
            activeClass: "vc_active",
            sortingPlaceholder: "vc_placeholder",
            events: {
                "click > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > .vc_controls .vc_control-btn-edit": "editElement",
                "click > .vc_controls .vc_control-btn-clone": "clone",
                "click > .vc_controls .vc_control-btn-copy": "copy",
                "click > .vc_controls .vc_control-btn-paste": "paste",
                "click > .vc_controls .vc_control-btn-prepend": "clickPrependSection",
                "click .vc_tta-section-append": "clickAppendSection"
            },
            initialize: function(params) {
                window.VcBackendTtaViewInterface.__super__.initialize.call(this, params), _.bindAll(this, "updateSorting")
            },
            render: function() {
                return window.VcBackendTtaViewInterface.__super__.render.call(this), this.$el.addClass("vc_tta-container vc_tta-o-non-responsive"), this
            },
            setContent: function() {
                this.$content = this.$el.find("> .wpb_element_wrapper .vc_tta-panels")
            },
            clickAppendSection: function(e) {
                e && e.preventDefault && e.preventDefault(), this.addSection()
            },
            clickPrependSection: function(e) {
                e && e.preventDefault && e.preventDefault(), this.addSection(!0)
            },
            addSection: function(prepend) {
                prepend = {
                    shortcode: "vc_tta_section",
                    params: {
                        title: this.defaultSectionTitle
                    },
                    parent_id: this.model.get("id"),
                    order: _.isBoolean(prepend) && prepend ? vc.add_element_block_view.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
                    prepend: prepend
                };
                return vc.shortcodes.create(prepend)
            },
            findSection: function(modelId) {
                return this.$content.children('[data-model-id="' + modelId + '"]')
            },
            getIndex: function($element) {
                return $element.index()
            },
            buildSortable: function($element) {
                return !("edit" === vc_user_access().getState("shortcodes") || !vc_user_access().shortcodeAll("vc_tta_section")) && $element.sortable({
                    forcePlaceholderSize: !0,
                    placeholder: this.sortingPlaceholder,
                    helper: this.renderSortingPlaceholder,
                    scroll: !0,
                    cursor: "move",
                    cursorAt: {
                        top: 20,
                        left: 16
                    },
                    start: function(event, ui) {},
                    over: function(event, ui) {},
                    stop: function(event, ui) {
                        ui.item.attr("style", "")
                    },
                    update: this.updateSorting,
                    items: this.sortableSelector
                })
            },
            updateSorting: function(event, ui) {
                var self;
                if (!vc_user_access().shortcodeAll("vc_tta_section")) return !1;
                (self = this).$sortable.find(this.sortableSelector).each(function() {
                    var $this = $(this),
                        modelId = $this.attr(self.sortableUpdateModelIdSelector),
                        modelId = vc.shortcodes.get(modelId);
                    vc.storage.lock(), modelId.save({
                        order: self.getIndex($this)
                    })
                }), vc.storage.unlock(), vc.storage.save()
            },
            makeFirstSectionActive: function() {
                this.$content.children(":first-child").addClass(this.activeClass)
            },
            checkForActiveSection: function() {
                this.$content.children("." + this.activeClass).length || this.makeFirstSectionActive()
            },
            changeActiveSection: function(modelId) {
                this.$content.children(".vc_tta-panel." + this.activeClass).removeClass(this.activeClass), this.findSection(modelId).addClass(this.activeClass)
            },
            changedContent: function(view) {
                view = window.VcBackendTtaViewInterface.__super__.changedContent.call(this, view);
                return this.checkForActiveSection(), this.buildSortable(this.$sortable), view
            },
            notifySectionChanged: function(model) {
                var view = model.get("view");
                _.isObject(view) && (model = model.getParam("title"), _.isString(model) && model.length || (model = this.defaultSectionTitle), view.$el.find(".vc_tta-panel-title a .vc_tta-title-text").text(model))
            },
            notifySectionRendered: function(model) {},
            getNextTab: function($viewTab) {
                var $navigationSections = this.$navigation.children(),
                    lastIndex = $navigationSections.length - 2,
                    $viewTab = $viewTab.index(),
                    lastIndex = $viewTab !== lastIndex ? $navigationSections.eq($viewTab + 1) : $navigationSections.eq($viewTab - 1);
                return lastIndex
            },
            renderSortingPlaceholder: function(event, element) {
                return vc.app.renderPlaceholder(event, element)
            }
        }), window.VcBackendTtaTabsView = window.VcBackendTtaViewInterface.extend({
            sortableSelector: "> [data-vc-tab]",
            sortableSelectorCancel: ".vc-non-draggable-container",
            sortablePlaceholderClass: "vc_placeholder-tta-tab",
            navigationSectionTemplate: null,
            navigationSectionTemplateParsed: null,
            $navigationSectionAdd: null,
            sortingPlaceholder: "vc_placeholder-tab vc_tta-tab",
            render: function() {
                return window.VcBackendTtaTabsView.__super__.render.call(this), this.$navigation = this.$el.find("> .wpb_element_wrapper .vc_tta-tabs-list"), this.$sortable = this.$navigation, this.$navigationSectionAdd = this.$navigation.children(".vc_tta-tab:first-child"), this.setNavigationSectionTemplate(this.$navigationSectionAdd.prop("outerHTML")), vc_user_access().shortcodeAll("vc_tta_section") ? (this.$navigationSectionAdd.addClass("vc_tta-section-append").removeAttr("data-vc-target-model-id").removeAttr("data-vc-tab").find("[data-vc-target]").html('<i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>').removeAttr("data-vc-tabs").removeAttr("data-vc-target").removeAttr("data-vc-target-model-id").removeAttr("data-vc-toggle"), "true" === this.$navigationSectionAdd.attr("data-hide-add-control") && this.$navigationSectionAdd.css("display", "none")) : this.$navigationSectionAdd.hide(), this
            },
            setNavigationSectionTemplate: function(html) {
                this.navigationSectionTemplate = html, this.navigationSectionTemplateParsed = vc.template(this.navigationSectionTemplate, vc.templateOptions.custom)
            },
            getNavigationSectionTemplate: function() {
                return this.navigationSectionTemplate
            },
            getParsedNavigationSectionTemplate: function(data) {
                return this.navigationSectionTemplateParsed(data)
            },
            changeNavigationSectionTitle: function(modelId, title) {
                this.findNavigationTab(modelId).find("[data-vc-target]").text(title)
            },
            changeActiveSection: function(modelId) {
                window.VcBackendTtaTabsView.__super__.changeActiveSection.call(this, modelId), this.$navigation.children("." + this.activeClass).removeClass(this.activeClass), this.findNavigationTab(modelId).addClass(this.activeClass)
            },
            notifySectionRendered: function(model) {
                var title, clonedFrom;
                window.VcBackendTtaTabsView.__super__.notifySectionRendered.call(this, model), title = model.getParam("title"), title = $(this.getParsedNavigationSectionTemplate({
                    model_id: model.get("id"),
                    section_title: _.isString(title) && 0 < title.length ? title : this.defaultSectionTitle
                })), model.get("cloned") ? (clonedFrom = model.get("cloned_from"), _.isObject(clonedFrom) && ((clonedFrom = this.$navigation.children('[data-vc-target-model-id="' + clonedFrom.id + '"]')).length ? title.insertAfter(clonedFrom) : title.insertBefore(this.$navigation.children(".vc_tta-section-append")))) : model.get("prepend") ? title.insertBefore(this.$navigation.children(":first-child")) : title.insertBefore(this.$navigation.children(":last-child"))
            },
            notifySectionChanged: function(model) {
                var title;
                window.VcBackendTtaTabsView.__super__.notifySectionChanged.call(this, model), title = model.getParam("title"), _.isString(title) && title.length || (title = this.defaultSectionTitle), this.changeNavigationSectionTitle(model.get("id"), title), model.view.$el.find("> .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_element-name").removeClass("vc_element-move"), model.view.$el.find("> .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_element-name .vc-c-icon-dragndrop").hide()
            },
            makeFirstSectionActive: function() {
                var $tab = this.$navigation.children(":first-child:not(.vc_tta-section-append)").addClass(this.activeClass);
                $tab.length && this.findSection($tab.data("vc-target-model-id")).addClass(this.activeClass)
            },
            findNavigationTab: function(modelId) {
                return this.$navigation.children('[data-vc-target-model-id="' + modelId + '"]')
            },
            removeSection: function(model) {
                var $nextTab, model = this.findNavigationTab(model.get("id"));
                model.hasClass(this.activeClass) && (($nextTab = this.getNextTab(model)).addClass(this.activeClass), this.changeActiveSection($nextTab.data("vc-target-model-id"))), model.remove()
            },
            renderSortingPlaceholder: function(event, currentItem) {
                var helper = currentItem,
                    currentItemWidth = currentItem.width() + 1,
                    currentItem = currentItem.height();
                return helper.width(currentItemWidth), helper.height(currentItem), helper
            }
        }), window.VcBackendTtaAccordionView = VcBackendTtaViewInterface.extend({
            sortableSelector: "> .vc_tta-panel:not(.vc_tta-section-append)",
            sortableSelectorCancel: ".vc-non-draggable",
            sortableUpdateModelIdSelector: "data-model-id",
            defaultSectionTitle: window.i18nLocale.section,
            render: function() {
                return window.VcBackendTtaTabsView.__super__.render.call(this), this.$navigation = this.$content, this.$sortable = this.$content, vc_user_access().shortcodeAll("vc_tta_section") || this.$content.find(".vc_tta-section-append").hide(), this
            },
            removeSection: function(model) {
                model = this.findSection(model.get("id"));
                model.hasClass(this.activeClass) && this.getNextTab(model).addClass(this.activeClass)
            },
            addShortcode: function(view) {
                var beforeShortcode = _.last(vc.shortcodes.filter(function(shortcode) {
                    return shortcode.get("parent_id") === this.get("parent_id") && parseFloat(shortcode.get("order")) < parseFloat(this.get("order"))
                }, view.model));
                beforeShortcode ? view.render().$el.insertAfter("[data-model-id=" + beforeShortcode.id + "]") : this.$content.prepend(view.render().el)
            }
        }), window.VcBackendTtaTourView = window.VcBackendTtaTabsView.extend({
            defaultSectionTitle: window.i18nLocale.section
        }), window.VcBackendTtaPageableView = window.VcBackendTtaTabsView.extend({
            defaultSectionTitle: window.i18nLocale.section
        }), window.VcBackendTtaSectionView = window.VcColumnView.extend({
            parentObj: null,
            events: {
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-delete": "deleteShortcode",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-prepend": "addElement",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-edit": "editElement",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-clone": "clone",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-copy": "copy",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_controls .vc_control-btn-paste": "paste",
                "click > .wpb_element_wrapper > .vc_tta-panel-body > .vc_empty-container": "addToEmpty"
            },
            setContent: function() {
                this.$content = this.$el.find("> .wpb_element_wrapper > .vc_tta-panel-body > .vc_container_for_children")
            },
            render: function() {
                var parentObj;
                return window.VcBackendTtaSectionView.__super__.render.call(this), parentObj = vc.shortcodes.get(this.model.get("parent_id")), _.isObject(parentObj) && !_.isUndefined(parentObj.view) && (this.parentObj = parentObj), this.$el.addClass("vc_tta-panel"), this.$el.attr("style", ""), this.$el.attr("data-vc-toggle", "tab"), this.replaceTemplateVars(), this
            },
            replaceTemplateVars: function() {
                var title = this.model.getParam("title"),
                    $panelHeading = (_.isEmpty(title) && (title = this.parentObj && this.parentObj.defaultSectionTitle && this.parentObj.defaultSectionTitle.length ? this.parentObj.defaultSectionTitle : window.i18nLocale.section), this.$el.find(".vc_tta-panel-heading")),
                    template = vc.template($panelHeading.html(), vc.templateOptions.custom);
                $panelHeading.html(template({
                    model_id: this.model.get("id"),
                    section_title: title
                }))
            },
            getIndex: function() {
                return this.$el.index()
            },
            ready: function() {
                this.updateParentNavigation(), window.VcBackendTtaSectionView.__super__.ready.call(this)
            },
            updateParentNavigation: function() {
                _.isObject(this.parentObj) && this.parentObj.view && this.parentObj.view.notifySectionRendered && this.parentObj.view.notifySectionRendered(this.model)
            },
            deleteShortcode: function(e) {
                return e && e.preventDefault && e.preventDefault(), !0 === confirm(window.i18nLocale.press_ok_to_delete_section) && (1 === vc.shortcodes.where({
                    parent_id: this.model.get("parent_id")
                }).length ? (this.model.destroy(), this.parentObj && this.parentObj.destroy()) : (this.parentObj && this.parentObj.view && this.parentObj.view.removeSection && this.parentObj.view.removeSection(this.model), this.model.destroy()), !0)
            },
            changeShortcodeParams: function(model) {
                window.VcBackendTtaSectionView.__super__.changeShortcodeParams.call(this, model), _.isObject(this.parentObj) && this.parentObj.view && this.parentObj.view.notifySectionChanged && this.parentObj.view.notifySectionChanged(model)
            }
        }), vc.addTemplateFilter(function(string) {
            var random_id = VCS4() + "-" + VCS4();
            return string.replace(/tab\_id\=\"([^\"]+)\"/g, 'tab_id="$1' + random_id + '"')
        })
    }(window.jQuery),
    function($, _) {
        "use strict";
        var attachmentCompatRender, attachCb = [],
            media = wp.media,
            origFeaturedImageSet = media.featuredImage.set,
            origEditorSendAttachment = media.editor.send.attachment,
            l10n = i18nLocale,
            workflows = {};

        function processImages(attachments, callback) {
            var ids = attachments.models ? attachments.pluck("id") : attachments;
            $.ajax({
                dataType: "json",
                type: "POST",
                url: window.ajaxurl,
                data: {
                    action: "vc_media_editor_add_image",
                    filters: window.vc_selectedFilters,
                    ids: ids,
                    vc_inline: !0,
                    _vcnonce: window.vcAdminNonce
                }
            }).done(function(response) {
                var models, attachment, i;
                if ("function" == typeof callback) {
                    for (models = [], i = 0; i < response.data.ids.length; i++) attachment = (attachment = "function" == typeof attachment ? attachments.get(response.data.ids[i]) : attachments[response.data.ids[i]]) || media.model.Attachment.get(response.data.ids[i]), models.push(attachment);
                    var promises = function(models) {
                        for (var promises = [], i = 0; i < models.length; i++) models[i].get("url") || promises.push(models[i].fetch());
                        return promises
                    }(models);
                    $.when.apply($, promises).done(function() {
                        callback(models)
                    })
                }
            }).fail(function(response) {
                $(".media-modal-close").click(), attachCb = [], window.vc && window.vc.active_panel && window.i18nLocale && window.i18nLocale.error_while_saving_image_filtered && window.vc.active_panel.showMessage(window.i18nLocale.error_while_saving_image_filtered, "error"), window.console && window.console.warn && window.console.warn("processImages failed", response)
            }).always(function() {
                $(".media-modal").removeClass("processing-media")
            })
        }

        function previewFilter(attachmentId) {
            var $previewContainer, $preview, $filter = $(".media-frame:visible [data-vc-preview-image-filter=" + attachmentId + "]");
            $filter.length && ($previewContainer = $(".media-frame:visible .attachment-info .thumbnail-image").eq(-1), $preview = $previewContainer.find("img"), $previewContainer.addClass("loading"), $preview.data("original-src") || $preview.data("original-src", $preview.attr("src")), $filter.val().length ? $.ajax({
                type: "POST",
                dataType: "json",
                url: window.ajaxurl,
                data: {
                    action: "vc_media_editor_preview_image",
                    filter: $filter.val(),
                    attachment_id: attachmentId,
                    preferred_size: window.getUserSetting("imgsize", "medium"),
                    _vcnonce: window.vcAdminNonce
                }
            }).done(function(response) {
                response.success && response.data.src.length && $preview.attr("src", response.data.src)
            }).fail(function(jqXHR, textStatus, errorThrown) {
                window.console.warn("Filter preview failed:", textStatus, errorThrown)
            }).always(function() {
                $previewContainer.removeClass("loading")
            }) : ($preview.attr("src", $preview.data("original-src")), $previewContainer.removeClass("loading")))
        }
        attachmentCompatRender = _.extend(media.view.AttachmentCompat.prototype.render), media.view.AttachmentCompat.prototype.render = function() {
            var that = this,
                attachmentId = this.model.get("id");
            return attachmentCompatRender.call(this), _.defer(function() {
                var html, $container = that.controller.$el.find(".attachment-info"),
                    $input = that.controller.$el.find("[data-vc-preview-image-filter]");
                $container.length && $input.length && (html = '<div class="vc-filter-wrapper"><label class="setting vc-image-filter-setting">', html = (html += '<span class="name">' + $input.parent().find(".vc-filter-label").text() + "</span>") + $input[0].outerHTML + "</label></div>", $(".vc-filter-wrapper").length || $container.before(html), $input.parents("tr").remove()), void 0 !== window.vc_selectedFilters && void 0 !== window.vc_selectedFilters[attachmentId] && ($container = $(".media-frame:visible [data-vc-preview-image-filter=" + attachmentId + "]")).length && $container.val(window.vc_selectedFilters[attachmentId]).trigger("change"), previewFilter(attachmentId)
            }), this
        }, media.editor.send.attachment = function(props, attachment) {
            attachCb.push(attachment.id), processImages([attachment.id], function(newAttachment) {
                var attachment = newAttachment.slice(0).pop().attributes;
                origEditorSendAttachment(props, attachment).done(function(html) {
                    ! function origEditorSendAttachmentCallback(html, id) {
                        attachCb && attachCb[0] !== id ? setTimeout(function() {
                            origEditorSendAttachmentCallback(html, id)
                        }, 50) : (attachCb.shift(), media.editor.insert(html))
                    }(html, attachment.id)
                })
            })
        }, media.featuredImage.set = function(id) {
            -1 !== id ? $.ajax({
                type: "POST",
                url: window.ajaxurl,
                data: {
                    action: "vc_media_editor_add_image",
                    filters: window.vc_selectedFilters,
                    ids: [id],
                    _vcnonce: window.vcAdminNonce
                }
            }).done(function(response) {
                !0 === response.success && response.data.ids.length ? (response = response.data.ids.pop(), origFeaturedImageSet(response)) : origFeaturedImageSet(id)
            }).fail(function() {
                origFeaturedImageSet(id)
            }) : origFeaturedImageSet(id)
        }, media.controller.VcSingleImage = media.controller.FeaturedImage.extend({
            defaults: _.defaults({
                id: "vc_single-image",
                filterable: "uploaded",
                multiple: !1,
                toolbar: "vc_single-image",
                title: l10n.set_image,
                priority: 60,
                syncSelection: !1
            }, media.controller.Library.prototype.defaults),
            updateSelection: function() {
                var attachments, selection = this.get("selection"),
                    ids = media.vc_editor.getData();
                void 0 !== ids && "" !== ids && -1 !== ids && (attachments = _.map(ids.toString().split(/,/), function(id) {
                    id = media.model.Attachment.get(id);
                    return id.get("url") && id.get("url").length || id.fetch(), id
                })), selection.reset(attachments)
            }
        }), media.controller.VcGallery = media.controller.VcSingleImage.extend({
            defaults: _.defaults({
                id: "vc_gallery",
                title: l10n.add_images,
                toolbar: "main-insert",
                filterable: "uploaded",
                library: media.query({
                    type: "image"
                }),
                multiple: "add",
                editable: !0,
                priority: 60,
                syncSelection: !1
            }, media.controller.Library.prototype.defaults)
        }), media.VcSingleImage = {
            getData: function() {
                return this.$hidden_ids.val()
            },
            set: function(selection) {
                var template = vc.template($("#vc_settings-image-block").html(), vc.templateOptions.custom);
                return this.$img_ul.html(template(selection)), this.$clear_button.show(), this.$hidden_ids.val(selection.id).trigger("change"), !1
            },
            frame: function(element) {
                return window.vc_selectedFilters = {}, this.element = element, this.$button = $(this.element), this.$block = this.$button.closest(".edit_form_line"), this.$hidden_ids = this.$block.find(".gallery_widget_attached_images_ids"), this.$img_ul = this.$block.find(".gallery_widget_attached_images_list"), this.$clear_button = this.$img_ul.next(), this._frame || (this._frame = media({
                    state: "vc_single-image",
                    states: [new media.controller.VcSingleImage]
                }), this._frame.on("toolbar:create:vc_single-image", function(toolbar) {
                    this.createSelectToolbar(toolbar, {
                        text: l10n.set_image,
                        close: !1
                    })
                }, this._frame), this._frame.state("vc_single-image").on("select", this.select)), this._frame
            },
            select: function() {
                var selection = this.get("selection");
                vc.events.trigger("click:media_editor:add_image", selection, "single")
            }
        }, media.view.MediaFrame.VcGallery = media.view.MediaFrame.Post.extend({
            createStates: function() {
                this.states.add([new media.controller.VcGallery])
            },
            bindHandlers: function() {
                media.view.MediaFrame.Select.prototype.bindHandlers.apply(this, arguments), this.on("toolbar:create:main-insert", this.createToolbar, this);
                _.each({
                    content: {
                        embed: "embedContent",
                        "edit-selection": "editSelectionContent"
                    },
                    toolbar: {
                        "main-insert": "mainInsertToolbar"
                    }
                }, function(regionHandlers, region) {
                    _.each(regionHandlers, function(callback, handler) {
                        this.on(region + ":render:" + handler, this[callback], this)
                    }, this)
                }, this)
            },
            mainInsertToolbar: function(view) {
                var controller = this;
                this.selectionStatusToolbar(view), view.set("insert", {
                    style: "primary",
                    priority: 80,
                    text: l10n.add_images,
                    requires: {
                        selection: !0
                    },
                    click: function() {
                        var state = controller.state(),
                            selection = state.get("selection");
                        vc.events.trigger("click:media_editor:add_image", selection, "gallery"), state.trigger("insert", selection)
                    }
                })
            }
        }), media.vc_editor = _.clone(media.editor), _.extend(media.vc_editor, {
            $vc_editor_element: null,
            getData: function() {
                return media.vc_editor.$vc_editor_element.closest(".edit_form_line").find(".gallery_widget_attached_images_ids").val()
            },
            insert: function(images) {
                var $block = media.vc_editor.$vc_editor_element.closest(".edit_form_line"),
                    $hidden_ids = $block.find(".gallery_widget_attached_images_ids"),
                    $block = $block.find(".gallery_widget_attached_images_list"),
                    $thumbnails_string = "",
                    template = vc.template($("#vc_settings-image-block").html(), vc.templateOptions.custom);
                _.each(images, function(image) {
                    $thumbnails_string += template(image)
                }), $hidden_ids.val(_.map(images, function(image) {
                    return image.id
                }).join(",")).trigger("change"), $block.html($thumbnails_string)
            },
            open: function(id) {
                var workflow;
                return id = this.id(id), workflow = (workflow = this.get(id)) || this.add(id), window.vc_selectedFilters = {}, window.setTimeout(function() {
                    workflow.state().get("library").more()
                }, 50), workflow.open()
            },
            add: function(id, options) {
                var workflow = this.get(id);
                if (!workflow) {
                    if (workflows[id]) return workflows[id];
                    workflow = workflows[id] = new media.view.MediaFrame.VcGallery(_.defaults(options || {}, {
                        state: "vc_gallery",
                        title: l10n.add_images,
                        library: {
                            type: "image"
                        },
                        multiple: !0
                    }))
                }
                return workflow
            },
            init: function() {
                $("body").off("click.vcGalleryWidget").on("click.vcGalleryWidget", ".gallery_widget_add_images", function(event) {
                    event.preventDefault();
                    event = $(this);
                    media.vc_editor.$vc_editor_element = $(this), "true" === event.attr("use-single") ? media.VcSingleImage.frame(this).open("vc_editor") : (event.blur(), media.vc_editor.open("wpbakery"))
                })
            }
        }), _.bindAll(media.vc_editor, "open"), $(document).ready(function() {
            media.vc_editor.init()
        }), vc.events.on("click:media_editor:add_image", function(selection, type) {
            $(".media-modal").addClass("processing-media"), processImages(selection, function(newAttachments) {
                var objects, newAttachments = _.map(newAttachments, function(newAttachment) {
                    return newAttachment.attributes
                });
                switch (selection.reset(newAttachments), objects = _.map(selection.models, function(model) {
                    return model.attributes
                }), type = void 0 === type ? "" : type) {
                    case "gallery":
                        media.vc_editor.insert(objects);
                        break;
                    case "single":
                        media.VcSingleImage.set(objects[0])
                }
                $(".media-modal").removeClass("processing-media"), $(".media-modal-close").click()
            })
        }), $("body").on("change", "[data-vc-preview-image-filter]", function() {
            var id = $(this).data("vcPreviewImageFilter");
            void 0 === window.vc_selectedFilters && (window.vc_selectedFilters = {}), window.vc_selectedFilters[id] = $(this).val(), previewFilter(id)
        })
    }(window.jQuery, window._),
    function($) {
        "use strict";
        var vcPointerMessage = function(target, pointerOptions, texts) {
            this.target = target, this.$pointer = null, this.texts = texts, this.pointerOptions = pointerOptions, this.init()
        };
        vcPointerMessage.prototype = {
            init: function() {
                _.bindAll(this, "openedEvent", "reposition")
            },
            show: function() {
                this.$pointer = $(this.target), this.$pointer.data("vcPointerMessage", this), this.pointerOptions.opened = this.openedEvent, this.$pointer.addClass("vc-with-vc-pointer").pointer(this.pointerOptions).pointer("open"), $(window).on("resize.vcPointer", this.reposition)
            },
            domButtonsWrapper: function() {
                return $('<div class="vc_wp-pointer-controls" />')
            },
            domCloseBtn: function() {
                return $('<a class="vc_pointer-close close">' + this.texts.finish + "</a>")
            },
            domNextBtn: function() {
                return $('<button class="button button-primary button-large vc_wp-pointers-next">' + this.texts.next + '<i class="vc_pointer-icon"></i></button>')
            },
            domPrevBtn: function() {
                return $('<button class="button button-primary button-large vc_wp-pointers-prev"><i class="vc_pointer-icon"></i>' + this.texts.prev + "</button> ")
            },
            openedEvent: function(a, b) {
                var offset = b.pointer.offset();
                b.pointer.css("z-index", 1e5), offset && offset.top && $("body").scrollTop(80 < offset.top ? offset.top - 80 : 0)
            },
            reposition: function() {
                this.$pointer.pointer("reposition")
            },
            close: function() {
                this.$pointer && this.$pointer.removeClass("vc-with-vc-pointer").pointer("close"), $(window).off("resize.vcPointer")
            }
        }, window.vcPointerMessage = vcPointerMessage
    }(window.jQuery),
    function($) {
        "use strict";
        var vcPointersController = function(Pointer, texts) {
            this.pointers = Pointer && Pointer.messages || [], this._texts = texts, this.pointerId = Pointer && Pointer.pointer_id ? Pointer.pointer_id : "", this.pointerData = {}, this._index = 0, this.messagesDismissed = !1, this.init()
        };
        vcPointersController.prototype = {
            init: function() {
                _.bindAll(this, "show", "clickEventClose", "clickEventNext", "clickEventPrev", "buttonsEvent"), this.build()
            },
            getPointer: function(index) {
                return this.pointerData = this.pointers[index] && this.pointers[index].target ? this.pointers[index] : null, $("body").hasClass("vc_editor") && "#vc_ui-panel-post-custom-layout" === this.pointerData.target && "none" === $(this.pointerData.target).css("display") && this.next(), this.pointerData && this.pointerData.options ? new vcPointerMessage(this.pointerData.target, this.buildOptions(this.pointerData.options), this._texts) : null
            },
            buildOptions: function(data) {
                return data.buttonsEvent && _.isFunction(window[data.buttonsEvent]) ? data.buttons = _.bind(window[data.buttonsEvent], this) : data.buttons = this.buttonsEvent, data.vcPointerController = this, data
            },
            build: function() {
                if (this.pointer = this.getPointer(this._index), vc.events.once("backendEditor.close", this.close, this), !this.pointer) return !1;
                this.setShowEventHandler()
            },
            show: function() {
                this.pointer.show(), this.setCloseEventHandler(), vc.events.trigger("vcPointer:show")
            },
            setShowEventHandler: function() {
                var showEvent;
                this.pointerData.showCallback && window[this.pointerData.showCallback] ? window[this.pointerData.showCallback].call(this) : this.pointerData.showEvent ? this.pointerData.showEvent.match(/\s/) ? 1 < (showEvent = this.pointerData.closeEvent.split(/\s+(.+)?/)).length && $(showEvent[1]).one(showEvent[0], this.show) : vc.events.once(this.pointerData.showEvent, this.show) : this.show()
            },
            setCloseEventHandler: function() {
                var closeEvent;
                this.pointerData.closeCallback && window[this.pointerData.closeCallback] ? window[this.pointerData.closeCallback].call(this) : this.pointerData.closeEvent ? this.pointerData.closeEvent.match(/\s/) ? (closeEvent = this.pointerData.closeEvent.split(/\s+(.+)?/), $(closeEvent[1] || this.$pointer).one(closeEvent[1] && closeEvent[0] ? closeEvent[0] : "click", this.clickEventNext)) : vc.events.once(this.pointerData.closeEvent, this.nextOnEvent, this) : this.pointer.$pointer && 0 < this.pointer.$pointer.length && $(this.pointer.$pointer).one("click", this.clickEventNext)
            },
            nextOnEvent: function() {
                this.close(), this.next()
            },
            next: function() {
                this._index++, this.build()
            },
            prev: function() {
                this._index--, this.build()
            },
            close: function() {
                this.pointer && (this.pointer.close(), this.pointerData = null, this.pointer = null, vc.events.trigger("vcPointer:close", this))
            },
            buttonsEvent: function() {
                var $closeBtn = this.pointer.domCloseBtn(),
                    $nextBtn = this.pointer.domNextBtn(),
                    $prevBtn = this.pointer.domPrevBtn();
                return $closeBtn.bind("click.vcPointer", this.clickEventClose), $closeBtn = this.pointer.domButtonsWrapper().append($closeBtn), 0 < this._index && ($prevBtn.bind("click.vcPointer", this.clickEventPrev), $closeBtn.addClass("vc_wp-pointer-controls-prev").append($prevBtn)), this._index + 1 < this.pointers.length && ($nextBtn.bind("click.vcPointer", this.clickEventNext), $closeBtn.addClass("vc_wp-pointer-controls-next").append($nextBtn)), $closeBtn
            },
            clickEventClose: function() {
                this.close(), this.dismissMessages()
            },
            clickEventNext: function() {
                this.close(), this.next()
            },
            clickEventPrev: function() {
                this.close(), this.prev()
            },
            dismissMessages: function() {
                if (this.messagesDismissed) return !1;
                $.post(window.ajaxurl, {
                    pointer: this.pointerId,
                    action: "dismiss-wp-pointer"
                }), this.messagesDismissed = !0
            }
        }, window.vcPointersController = vcPointersController
    }(window.jQuery),
    function($) {
        "use strict";
        vc.events.on("app.render", function() {
            window.vcPointer && window.vcPointer.pointers && window.vcPointer.pointers.length && _.each(vcPointer.pointers, function(pointer) {
                new vcPointersController(pointer, vcPointer.texts)
            }, this)
        }), vc.events.on("vcPointer:show", function() {
            vc.app.disableFixedNav = !0
        }), vc.events.on("vcPointer:close", function() {
            vc.app.disableFixedNav = !1
        }), window.vcPointersEditorsTourEvents = function() {
            var $close_btn = this.pointer.domCloseBtn();
            return $close_btn.bind("click.vcPointer", this.clickEventClose), $close_btn
        }, window.vcPointersShowOnContentElementControls = function() {
            this.pointer && $(this.pointer.target).length ? ($(this.pointer.target).parent().addClass("vc-with-vc-pointer-controls"), this.show(), $("#wpb_wpbakery").one("click", function() {
                $(".vc-with-vc-pointer-controls").removeClass("vc-with-vc-pointer-controls")
            })) : vc.events.once("shortcodes:add", vcPointersShowOnContentElementControls, this)
        }, window.vcPointersSetInIFrame = function() {
            this.pointerData && vc.frame_window.jQuery(this.pointerData.target).length ? (this.pointer = new vc.frame_window.vcPointerMessage(this.pointerData.target, this.buildOptions(this.pointerData.options), this._texts), this.show(), this.pointer.$pointer.closest(".vc_controls").addClass("vc-with-vc-pointer-controls")) : vc.events.once("shortcodeView:ready", vcPointersSetInIFrame, this)
        }, window.vcPointersCloseInIFrame = function() {
            var controller = this,
                _$ = vc.frame_window.jQuery;
            _$("body").one("click", function() {
                _$(".vc-with-vc-pointer-controls").removeClass("vc-with-vc-pointer-controls"), controller.nextOnEvent()
            })
        }
    }(window.jQuery),
    function() {
        "use strict";
        var undo_redo_core = {
                stack: [],
                stackPosition: 0,
                stackHash: JSON.stringify(""),
                zeroState: null,
                locked: !1,
                add: function(data) {
                    null === this.zeroState && this.setZeroState(data), this.stackHash !== JSON.stringify(data) && (this.can("redo") && (this.stack = this.stack.slice(0, this.stackPosition)), this.stack.push(data), this.stackPosition = this.stack.length, this.stackHash = JSON.stringify(this.get()))
                },
                can: function(what) {
                    var result = !1;
                    return "undo" === what ? result = 0 < this.stack.length && 0 < this.stackPosition : "redo" === what && (result = 0 < this.stack.length && this.stackPosition < this.stack.length), result
                },
                undo: function() {
                    this.can("undo") && (--this.stackPosition, this.stackHash = JSON.stringify(this.get()))
                },
                redo: function() {
                    this.can("redo") && (this.stackPosition += 1, this.stackHash = JSON.stringify(this.get()))
                },
                set: function(index) {
                    return this.stackPosition < index && (this.stack = this.stack.slice(index - this.stackPosition), this.stackHash = JSON.stringify(this.get()), !0)
                },
                get: function() {
                    return this.stackPosition < 1 ? this.zeroState : this.stack[this.stackPosition - 1]
                },
                setZeroState: function(data) {
                    this.zeroState = data, this.stackHash = JSON.stringify(this.get())
                }
            },
            undo_redo_api = {
                add: function(document) {
                    !0 !== undo_redo_core.locked && (undo_redo_core.add(document), window.vc.events.trigger("undoredo:add", document))
                },
                getCurrentPosition: function() {
                    return undo_redo_core.stackPosition
                },
                undo: function() {
                    return undo_redo_core.undo(), window.vc.events.trigger("undoredo:undo"), undo_redo_api.get()
                },
                redo: function() {
                    return undo_redo_core.redo(), window.vc.events.trigger("undoredo:redo"), undo_redo_api.get()
                },
                get: function() {
                    return undo_redo_core.get()
                },
                canUndo: function() {
                    return !this.isLocked() && undo_redo_core.can("undo")
                },
                canRedo: function() {
                    return !this.isLocked() && undo_redo_core.can("redo")
                },
                setZeroState: function(data) {
                    null === undo_redo_core.zeroState ? this.add(data) : undo_redo_core.setZeroState(data)
                },
                lock: function() {
                    undo_redo_core.locked = !0, window.vc.events.trigger("undoredo:lock")
                },
                unlock: function() {
                    undo_redo_core.locked = !1, window.vc.events.trigger("undoredo:unlock")
                },
                isLocked: function() {
                    return !0 === undo_redo_core.locked
                }
            };
        void 0 === window.vc && (window.vc = {}), window.vc.undoRedoApi = undo_redo_api
    }(),
    function($) {
        "use strict";
        $(function() {
            var $undoControl, $redoControl, renderNewContent;
            window.vc && window.vc.events && ($undoControl = $("#vc_navbar-undo"), $redoControl = $("#vc_navbar-redo"), renderNewContent = function(content) {
                vc.storage.setContent(content), vc.shortcodes.fetch({
                    reset: !0
                }), _.delay(function() {
                    window.vc.undoRedoApi.unlock()
                }, 50)
            }, window.vc.events.on("undoredo:add undoredo:undo undoredo:redo undoredo:lock undoredo:unlock", _.debounce(function() {
                $undoControl.attr("disabled", !window.vc.undoRedoApi.canUndo()), $redoControl.attr("disabled", !window.vc.undoRedoApi.canRedo())
            }, 150)), $undoControl.on("click.vc-undo", function(e) {
                $(this).is("[disabled]") || window.vc.undoRedoApi.isLocked() ? e && e.preventDefault && e.preventDefault() : (vc.closeActivePanel(), window.vc.undoRedoApi.lock(), e = window.vc.undoRedoApi.undo(), renderNewContent(e))
            }), $redoControl.on("click.vc-redo", function(e) {
                $(this).is("[disabled]") || window.vc.undoRedoApi.isLocked() ? e && e.preventDefault && e.preventDefault() : (vc.closeActivePanel(), window.vc.undoRedoApi.lock(), e = window.vc.undoRedoApi.redo(), renderNewContent(e))
            }))
        })
    }(window.jQuery), window.vc || (window.vc = {}),
    function() {
        "use strict";

        function pasteIntoEditor(model, builder, text) {
            "fromLocalStorage" === text && (text = JSON.parse(localStorage.getItem("copiedShortcode")));
            for (var parent = !1, shortcodes = (model && (parent = model.get("id")), Object.values(builder ? vc.ShortcodesBuilder.prototype.parse({}, text, parent) : vc.storage.parseContent({}, text, parent))), i = 0; i < shortcodes.length; i++) {
                var shortcode = shortcodes[i];
                if (isPasteDisabled(model, shortcode, shortcodes)) break;
                shortcodeToPaste(0 === i && model, shortcode, shortcodes, builder)
            }
            builder && builder.render()
        }
        vc.pasteShortcode = function(model, builder, content) {
            content ? pasteIntoEditor(model, builder, content) : navigator.clipboard ? navigator.permissions.query({
                name: "clipboard-read"
            }).then(function(permission) {
                "granted" === permission.state ? navigator.clipboard.readText().then(function(cliptext) {
                    pasteIntoEditor(model, builder, cliptext)
                }) : pasteIntoEditor(model, builder, "fromLocalStorage")
            }).catch(function() {
                pasteIntoEditor(model, builder, "fromLocalStorage")
            }) : pasteIntoEditor(model, builder, "fromLocalStorage")
        }, vc.copyShortcode = function(model) {
            var model = vc.shortcodes.createShortcodeString(model);
            localStorage.setItem("copiedShortcode", JSON.stringify(model)), model = model, navigator.clipboard ? navigator.clipboard.writeText(model) : function(text) {
                var textArea = document.createElement("textarea");
                textArea.value = text, textArea.style.top = "0", textArea.style.left = "0", textArea.style.position = "fixed", document.body.appendChild(textArea), textArea.focus(), textArea.select();
                try {
                    document.execCommand("copy")
                } catch (err) {
                    console.error("Unable to copy", err)
                }
                document.body.removeChild(textArea)
            }(model)
        };
        var isPasteDisabled = function(model, shortcode, shortcodes) {
                var isModelSection, isModelColumn, isModelColumnInner, isModelRowInner, isModelTtaSection, isTopShortcodeRow, isTopShortcodeRowInner, isTopShortcodeSection, containerPreventsShortcode, isShortcodeContainer, shortcodeRejectsAsChild, isDisabled = !0;
                return model ? (shortcode = (model = getPasteConditionVariables(model, shortcode, shortcodes)).isModelRow, isModelSection = model.isModelSection, isModelColumn = model.isModelColumn, isModelColumnInner = model.isModelColumnInner, isModelRowInner = model.isModelRowInner, isModelTtaSection = model.isModelTtaSection, isTopShortcodeRow = model.isTopShortcodeRow, isTopShortcodeRowInner = model.isTopShortcodeRowInner, isTopShortcodeSection = model.isTopShortcodeSection, containerPreventsShortcode = model.containerPreventsShortcode, isShortcodeContainer = model.isShortcodeContainer, shortcodeRejectsAsChild = model.shortcodeRejectsAsChild, model = model.containerRejectsAsParent, isDisabled = isShortcodeContainer && (containerPreventsShortcode && !(isModelTtaSection && isTopShortcodeRowInner) || !containerPreventsShortcode && isModelTtaSection && isTopShortcodeRow || shortcode && !(isTopShortcodeRow || isTopShortcodeSection) || isModelRowInner && !isTopShortcodeRowInner || isModelColumn && isTopShortcodeRow || isModelColumnInner && (isTopShortcodeRowInner || isTopShortcodeRow)) || !isShortcodeContainer && (shortcode || isModelRowInner) || (shortcodeRejectsAsChild || model) && !(isTopShortcodeSection && (isModelSection || shortcode))) : "vc_row" === shortcodes[0].shortcode && (isDisabled = !1), isDisabled
            },
            shortcodeToPaste = function(model, shortcode, shortcodes, builder) {
                var isModelRowInner, isModelSection, isTopShortcodeRow, isTopShortcodeRowInner, isTopShortcodeSection, model_paste = Object.assign({}, shortcode);
                return model && (shortcodes = (shortcode = getPasteConditionVariables(model, shortcode, shortcodes)).isModelRow, isModelRowInner = shortcode.isModelRowInner, isModelSection = shortcode.isModelSection, isTopShortcodeRow = shortcode.isTopShortcodeRow, isTopShortcodeRowInner = shortcode.isTopShortcodeRowInner, isTopShortcodeSection = shortcode.isTopShortcodeSection, shortcode = shortcode.isModelContainer, model_paste.params.tab_id && (model_paste.params.tab_id = Date.now() + "-" + vc_guid()), isModelRowInner && isTopShortcodeRowInner || shortcodes && isTopShortcodeRow || !model.get("parent_id") && (!isModelSection || !isTopShortcodeRow) ? (builder && (model_paste.place_after_id = model.get("id")), model_paste.parent_id = model.get("parent_id")) : shortcode && !shortcodes && (model_paste.parent_id = model.get("id")), (shortcodes || isModelRowInner) && (isTopShortcodeRow || isTopShortcodeRowInner) || (shortcodes || isModelSection) && isTopShortcodeSection) && (model_paste.order = parseFloat(model.get("order")) + vc.clone_index), builder ? (builder.create(model_paste), builder.last()) : vc.shortcodes.create(model_paste)
            },
            getPasteConditionVariables = function(model, shortcode, shortcodes) {
                var model = model.get("shortcode"),
                    allowedContainerElement = vc.map[model].allowed_container_element,
                    asParent = vc.map[model].as_parent && vc.map[model].as_parent.only,
                    asChild = vc.map[shortcodes[0].shortcode].as_child && vc.map[shortcodes[0].shortcode].as_child.only;
                return {
                    isModelRow: "vc_row" === model,
                    isModelSection: "vc_section" === model,
                    isModelColumn: "vc_column" === model,
                    isModelColumnInner: "vc_column_inner" === model,
                    isModelRowInner: "vc_row_inner" === model,
                    isModelTtaSection: "vc_tta_section" === model,
                    isModelContainer: vc.map[model].is_container,
                    isTopShortcodeRow: "vc_row" === shortcodes[0].shortcode,
                    isTopShortcodeRowInner: "vc_row_inner" === shortcodes[0].shortcode,
                    isTopShortcodeSection: "vc_section" === shortcodes[0].shortcode,
                    containerPreventsShortcode: "string" == typeof allowedContainerElement && !allowedContainerElement.includes(shortcodes[0].shortcode) || !1 === allowedContainerElement,
                    isShortcodeContainer: vc.map[shortcodes[0].shortcode].is_container,
                    shortcodeRejectsAsChild: "string" == typeof asChild && !asChild.includes(model),
                    containerRejectsAsParent: "string" == typeof asParent && !asParent.includes(shortcodes[0].shortcode)
                }
            }
    }(),
    function($) {
        "use strict";
        var $aiModal = $("#vc_ui-helper-modal-ai"),
            $wpwrap = $("#wpwrap"),
            $insertButton = $aiModal.find('[data-vc-ui-element="button-save"]');

        function showErrorMessage(message) {
            window.vc.showMessage(message, "error", 1e4, "#vc_ui-helper-modal-ai .vc_ui-panel-window-inner")
        }

        function closeModal(e) {
            $(e.target).closest('[data-vc-ui-element="button-close"]').length && ($aiModal.removeClass("vc_active"), $aiModal.off("click", closeModal), $aiModal.removeData(), $aiModal.find(" .vc_ui-panel-content-container").addClass("vc_ui-hidden"), $aiModal.find(" .vc_ui-helper-modal-ai-placeholder").addClass("vc_ui-hidden"), $insertButton.hide())
        }
        $wpwrap.on("click", ".vc_ui-icon-ai", function(e) {
            var e = $(e.currentTarget),
                $currentParamContainer = e.closest(".vc_shortcode-param"),
                currentParamData = null,
                iconData = e.data();
            $currentParamContainer.length && (currentParamData = $currentParamContainer.data());
            var aiElementType = iconData.wpbAiElementType || "textarea",
                aiElementId = iconData.fieldId || aiElementType;
            $aiModal.find(".vc_ui-helper-modal-ai-preloader").length || $aiModal.find(".vc_ui-post-settings-header-container").after('<div class="vc_ui-helper-modal-ai-preloader"><div class="vc_ui-wp-spinner vc_ui-wp-spinner-dark vc_ui-wp-spinner-lg"></div></div>');
            (function(aiElementType, aiElementId) {
                aiElementType = {
                    action: "wpb_ai_get_modal_data",
                    data: {
                        ai_element_type: aiElementType,
                        ai_element_id: aiElementId
                    },
                    _vcnonce: window.vcAdminNonce
                };
                $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    data: aiElementType
                }).done(function(response) {
                    !0 === response.success ? (void 0 === window.vc.ai_modal_view ? window.vc.ai_modal_view = new vc.AiFormView({
                        el: "#vc_ui-helper-modal-ai",
                        data: response.data
                    }) : window.vc.ai_modal_view.render(response.data), $aiModal.find(" .vc_ui-panel-content-container").scrollTop(0), $aiModal.find(".vc_ui-helper-modal-ai-preloader").remove(), $aiModal.find(" .vc_ui-panel-content-container").removeClass("vc_ui-hidden")) : response && response.data && response.data[0] && response.data[0].code && response.data[0].message ? (console.error(response.data[0].message), showErrorMessage(response.data[0].message)) : (console.error(window.i18nLocale.ai_response_error), showErrorMessage(window.i18nLocale.ai_response_error))
                }).fail(function(response) {
                    console.error(window.i18nLocale.ai_response_error), _this.resetButton(), showErrorMessage(window.i18nLocale.ai_response_error)
                })
            })(aiElementType, aiElementId), currentParamData ? (aiElementType = $currentParamContainer.find("." + currentParamData.param_type), $aiModal.data("element", aiElementType), $.each(currentParamData, function(key, value) {
                $aiModal.data(key, value)
            })) : iconData.fieldId && (aiElementId = e.closest(".edit_form_line"), $currentParamContainer = e.closest(".vc_ui-settings-text-wrapper"), aiElementType = null, aiElementId.length ? aiElementType = aiElementId.find("#" + iconData.fieldId) : $currentParamContainer.length && (aiElementType = $currentParamContainer.siblings("#" + iconData.fieldId)), $aiModal.data("fieldId", iconData.fieldId), $aiModal.data("element", aiElementType));
            $aiModal.addClass("vc_active"), $aiModal.on("click", closeModal)
        })
    }(window.jQuery),
    function($) {
        "use strict";
        window.vc.AiFormView = Backbone.View.extend({
            events: {
                "click .vc_ai-generate-button": "generateContent",
                'change [name="contentType"]': "changeContentType",
                'input [name="prompt"]': "changePrompt",
                'click [data-vc-ui-element="button-save"]': "insertContent"
            },
            seconds: 0,
            minutes: 0,
            timerInterval: null,
            isGenerating: !1,
            maxWaitingCacheInterval: 9e5,
            maxPromptLength: 2e3,
            initialize: function(options) {
                this.toggleModalPromoClass(options.data.type), this.$el.find(".vc_ui-helper-modal-ai-preloader").after(options.data.content), this.setFormElements()
            },
            render: function(options) {
                return this.timerInterval && this.clearTimer(), this.toggleModalPromoClass(options.type), this.$form.after(options.content), this.$form.remove(), this.setFormElements(), this
            },
            setFormElements: function() {
                this.$form = this.$el.find(".vc_ui-panel-content-container"), this.$generate_button = this.$el.find(".vc_ai-generate-button"), this.$close_button = this.$el.find('[data-vc-ui-element="button-close"]'), this.$insert_button = this.$el.find('[data-vc-ui-element="button-save"]'), this.$generated_content = this.$el.find(".wpb_ai-generated-content"), this.$prompt_field = this.$el.find('[name="prompt"]'), this.$generate_placeholder = this.$el.find(".vc_ui-helper-modal-ai-placeholder"), this.$generate_placeholder_timer = this.$generate_placeholder.find(".vc_ai-timer"), this.initialButtonText = this.$generate_button.text().trim(), this.contentType = this.$el.find('[name="contentType"]').val(), "new_content" !== this.contentType || this.$prompt_field.val().trim() || this.disableButton()
            },
            generateContent: function(e) {
                e.preventDefault();
                var _this = this,
                    e = this.$prompt_field.val().split(" "),
                    e = (this.maxPromptLength < e.length && this.$prompt_field.val(e.slice(0, this.maxPromptLength).join(" ")), this.$form.find(':visible:not([style*="display: none"]), [name="prompt"], input[type="hidden"]').serializeArray()),
                    cache_id = this.getUniqueCacheId(),
                    e = (e.push({
                        name: "cacheId",
                        value: cache_id
                    }), this.$generated_content.val(""), {
                        action: "wpb_ai_api_get_response",
                        data: e,
                        _vcnonce: window.vcAdminNonce
                    });
                this.isGenerating = !0, this.$generate_placeholder.removeClass("vc_ui-hidden"), this.timerInterval = setInterval(this.updateTimer.bind(this), 1e3), $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    timeout: 2e4,
                    data: e
                }).done(function(response) {
                    if (!_this.isGenerating) return !1;
                    !0 === response.success ? (_this.$generated_content.val(response.data), _this.resetButton(!0), _this.$insert_button.show()) : response && response.data && response.data[0] && response.data[0].code && response.data[0].message ? (console.error(response.data[0].code, response.data[0].message), _this.resetButton(!1), response = response.data[0].message.replace(/\\/g, ""), _this.showErrorMessage(response)) : (console.error(window.i18nLocale.ai_response_error), _this.resetButton(!1), _this.showErrorMessage(window.i18nLocale.ai_response_error))
                }).fail(function(response) {
                    if (!_this.isGenerating) return !1;
                    if (response && !response.statusText) console.error(window.i18nLocale.ai_response_error), _this.resetButton(!1), _this.showErrorMessage(window.i18nLocale.ai_response_error);
                    else if ("timeout" !== response.statusText) console.error(window.i18nLocale.ai_response_error), _this.resetButton(!1), _this.showErrorMessage(window.i18nLocale.ai_response_error);
                    else
                        for (var data = {
                                action: "wpb_ai_generate_content_check_cache",
                                data: {
                                    type: "generate-text",
                                    messaged_data: !0,
                                    cacheId: cache_id
                                },
                                _vcnonce: window.vcAdminNonce
                            }, timeouts = [], time_interval = 1e4; time_interval <= _this.maxWaitingCacheInterval; time_interval += 1e4) ! function(interval) {
                            timeouts.push(setTimeout(function() {
                                var output_value = _this.$generated_content.val();
                                if (output_value)
                                    for (var i = 0; i < timeouts.length; i++) "stop_cache_timeouts" === output_value && _this.$generated_content.val(""), clearTimeout(timeouts[i]);
                                else _this.processCachedRequest(_this, data, interval)
                            }, interval))
                        }(time_interval)
                })
            },
            processCachedRequest: function(_this, data, time_interval) {
                this.maxWaitingCacheInterval === time_interval ? (console.error(window.i18nLocale.ai_response_error), _this.resetButton(!1), _this.showErrorMessage(window.i18nLocale.ai_response_error)) : $.ajax({
                    type: "POST",
                    url: window.ajaxurl,
                    timeout: 1e4,
                    data: data
                }).done(function(response) {
                    if (!_this.isGenerating) return !1;
                    !0 === response.success && response.data && "cache_in_process" !== response.data && (_this.$generated_content.val(response.data), _this.resetButton(!0), _this.$insert_button.show()), !1 === response.success && response && response.data && response.data[0] && response.data[0].code && response.data[0].message && (_this.$generated_content.val("stop_cache_timeouts"), _this.resetButton(!1), response = response.data[0].message.replace(/\\/g, ""), _this.showErrorMessage(response))
                })
            },
            getUniqueCacheId: function() {
                return Date.now().toString(36) + Math.random().toString(36).slice(2)
            },
            disableButton: function() {
                this.$generate_button.prop("disabled", function(_, val) {
                    return !val
                }), this.isGenerateDisabled = !0
            },
            resetButton: function(isGenerated) {
                isGenerated = isGenerated ? "Regenerate" : this.initialButtonText;
                this.$generate_button.removeAttr("disabled style"), this.$generate_button.text(isGenerated), this.$generate_button.blur(), this.clearTimer()
            },
            clearTimer: function() {
                this.$generate_placeholder.addClass("vc_ui-hidden"), this.$generate_placeholder_timer.text("00:00"), clearInterval(this.timerInterval), this.seconds = 0, this.minutes = 0, this.isGenerating = !1
            },
            updateTimer: function() {
                this.seconds++, 60 === this.seconds && (this.seconds = 0, this.minutes++);
                var formattedMinutes = String(this.minutes).padStart(2, "0"),
                    formattedSeconds = String(this.seconds).padStart(2, "0");
                this.$generate_placeholder_timer.text(formattedMinutes + ":" + formattedSeconds)
            },
            changeContentType: function(e) {
                this.contentType = e.target.value;
                var elementData = this.$el.data(),
                    formFieldOptionalityList = (formFieldOptionalityList = $(e.target).find("option:selected").attr("data-form-fields-optionality")) ? formFieldOptionalityList.split("|") : [];
                this.hideFormFields(formFieldOptionalityList), this.$form.trigger("reset"), this.$form.find('[name="contentType"]').val(this.contentType), "improve_existing" === e.target.value || "translate" === e.target.value ? (this.$generate_button.text(window.i18nLocale.regenerate), formFieldOptionalityList = elementData.element.val(), "textarea_raw_html" === elementData.param_type ? formFieldOptionalityList = rawurldecode(base64_decode(formFieldOptionalityList.trim())) : "textarea_html" === elementData.param_type && (formFieldOptionalityList = window.tinymce.get(elementData.element.attr("id")).getContent()), this.$form.find('[name="prompt"]').val(formFieldOptionalityList), this.resetButton(!0)) : (this.$generate_button.text(window.i18nLocale.generate), this.$form.find('[name="prompt"]').val(""), this.disableButton())
            },
            changePrompt: function(e) {
                this.isGenerateDisabled && e.target.value ? (this.resetButton(!1), this.isGenerateDisabled = !1) : e.target.value || this.isGenerateDisabled || this.disableButton();
                var promptWords = e.target.value.split(" ");
                promptWords.length > this.maxPromptLength && (e.target.value = promptWords.slice(0, this.maxPromptLength).join(" "))
            },
            showErrorMessage: function(message) {
                window.vc.showMessage(message, "error", 1e4, "#vc_ui-helper-modal-ai .vc_ui-panel-window-inner")
            },
            insertContent: function() {
                var generatedContent = this.$el.find(".wpb_ai-generated-content").val();
                if (!generatedContent) return !1;
                var $textareaElement, textareaId, currentParamData = this.$el.data();
                "textarea_html" === currentParamData.param_type ? (textareaId = ($textareaElement = currentParamData.element).attr("id"), "new_content" === this.contentType && (generatedContent = $textareaElement.val() + " " + generatedContent), window.tinymce.get(textareaId).setContent(generatedContent), $textareaElement.val(generatedContent)) : ["textarea", "textfield", "textarea_raw_html"].includes(currentParamData.param_type) ? (textareaId = currentParamData.element, "new_content" === this.contentType && (generatedContent = textareaId.val() + " " + generatedContent), textareaId.val(generatedContent)) : currentParamData.fieldId && ("vc_page-title-field" === currentParamData.fieldId ? ("new_content" === this.contentType && (generatedContent = currentParamData.element.val() + " " + generatedContent), currentParamData.element.val(generatedContent)) : ["wpb_css_editor", "wpb_js_header_editor", "wpb_js_footer_editor"].includes(currentParamData.fieldId) && (generatedContent = window.ace.edit(currentParamData.fieldId).getValue() + "\n\n" + generatedContent, window.ace.edit(currentParamData.fieldId).setValue(generatedContent))), this.$close_button.click()
            },
            toggleModalPromoClass: function(type) {
                "promo" === type ? this.$el.addClass("vc_modal-ai-container--promo") : this.$el.removeClass("vc_modal-ai-container--promo")
            },
            hideFormFields: function(optionalityList) {
                this.$form.find("div[data-optional-form-field]").each(function() {
                    var $formField = $(this),
                        formFieldSlug = $formField.attr("data-optional-form-field");
                    optionalityList.includes(formFieldSlug) ? $formField.show() : $formField.hide()
                })
            }
        })
    }(window.jQuery);