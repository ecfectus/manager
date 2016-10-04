# Manager

[![Build Status](https://travis-ci.org/ecfectus/manager.svg?branch=master)](https://travis-ci.org/ecfectus/manager)

A simple driver based manager trait to use multiple "drivers" for a set of functionality.

Obviously inspired and mostly ported from the Laravel framework, this trait allows you to define multiple "drivers" that all implement an interface defined by the manager class.

Whats slightly different is the functionality is wrapped up as a trait and not a class that must be extended, which offers more flexibility.

On top of that it validates the instances returned, you must define at least one interface the drivers implement, if they dont an exception will be thrown.
