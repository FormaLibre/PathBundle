/**
 * Step Service
 */
(function () {
    'use strict';

    angular.module('StepModule').factory('StepService', [
        'IdentifierService',
        'ResourceService',
        function StepService(IdentifierService, ResourceService) {
            /**
             * Step object
             * @constructor
             */
            var Step = function Step(parent) {
                var name, lvl;

                if (parent) {
                    lvl  = parent.lvl + 1;
                    name = 'Step ' + lvl + '.' + (parent.children.length + 1);
                } else {
                    lvl = 0;
                    name = Translator.trans('root_default_name', {}, 'path_editor');
                }

                this.id                = IdentifierService.generateUUID();
                this.lvl               = lvl;
                this.name              = name;
                this.children          = [];
                this.activityId        = null;
                this.primaryResource   = null;
                this.resources         = [];
                this.excludedResources = [];
            };

            return {
                /**
                 * Generates a new empty step
                 *
                 * @param   {object} [parentStep]
                 * @returns {Step}
                 */
                new: function (parentStep) {
                    var newStep = new Step(parentStep);

                    if (parentStep) {
                        // Append new child to its parent
                        if (!parentStep.children instanceof Array) {
                            parentStep.children = [];
                        }
                        parentStep.children.push(newStep);
                    }

                    return newStep;
                },

                loadActivity: function (step, activityId) {
                    $http.get(Routing.generate('innova_path_load_activity', { nodeId: activityId }))
                        .success(function (data) {
                            this.setActivity(step, data);
                        }.bind(this));
                },

                /**
                 * Injects the Activity data into step
                 * @param step
                 * @param activity
                 */
                setActivity: function (step, activity) {
                    if (typeof activity !== 'undefined' && activity !== null && activity.length !== 0) {
                        // Populate step
                        step.activityId  = activity['id'];
                        step.name        = activity['name'];
                        step.description = activity['description'];

                        // Primary resources
                        var resource = ResourceService.new(activity['primaryResource']['type'], activity['primaryResource']['resourceId'], activity['primaryResource']['name']);
                        this.addPrimaryResource(step, resource);

                        // Secondary resources
                        if (null !== activity['resources']) {
                            for (var i = 0; i < activity['resources'].length; i++) {
                                var current = activity['resources'][i];

                                var resource = ResourceService.new(current['type'], current['resourceId'], current['name']);
                                this.addSecondaryResource(step, resource);
                            }
                        }

                        // Parameters
                        step.withTutor = activity['withTutor'];
                        step.who       = activity['who'];
                        step.where     = activity['where'];
                        step.duration  = activity['duration'];
                    }
                },

                /**
                 *
                 * @param {Step}     step
                 * @param {Resource} resource
                 */
                addPrimaryResource: function (step, resource) {
                    step.primaryResource = resource;
                },

                addSecondaryResource: function (step, resource) {
                    if (this.hasResource(step, resource)) {
                        if (!step.resources instanceof Array) {
                            step.resources = [];
                        }
                        step.resources.push(resource);
                    }
                },

                /**
                 * Remove selected resource from step
                 *
                 * @param   {object} step       current step
                 * @param   {string} resourceId resource to remove
                 * @returns {StepService}
                 */
                removeResource: function (step, resourceId) {
                    // Remove from included resources
                    if (typeof step.resources !== 'undefined' && null !== step.resources) {
                        for (var i = 0; i < step.resources.length; i++) {
                            if (resourceId === step.resources[i].id) {
                                step.resources.splice(i, 1);
                                break;
                            }
                        }
                    }

                    // Remove from excluded resources
                    if (typeof step.excludedResources != 'undefined' && null !== step.excludedResources) {
                        // Loop through excluded resource to remove reference to needle
                        for (var j = 0; j < step.excludedResources.length; j++) {
                            if (resourceId == step.excludedResources[j]) {
                                step.excludedResources.splice(j, 1);
                                break;
                            }
                        }
                    }

                    // Loop through children to remove propagation of the deleted resource
                    if (typeof step.children != 'undefined' && null !== step.children) {
                        for (var k = 0; k < step.children.length; k++) {
                            this.removeResource(step.children[k], resourceId);
                        }
                    }

                    return this;
                },

                /**
                 * Check if a step contains a resource
                 * @param {object} step
                 * @param {object} resource
                 */
                hasResource: function (step, resource) {
                    var resourceExists = false;
                    for (var i = 0; i < step.resources.length; i++) {
                        var stepResource = step.resources[i];
                        if (stepResource.resourceId === resource.resourceId) {
                            resourceExists = true;

                            break;
                        }
                    }

                    return resourceExists;
                }
            };
        }
    ]);
})();